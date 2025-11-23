<?php

namespace App\Http\Controllers;

use App\Models\{Track, TrackPlay, Reaction, Comment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 

class TrackController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $filter = $request->query('filter', 'all');
        $search = $request->query('search');
        $sort   = $request->query('sort', 'newest');

        $query = Track::query()->with(['reactions', 'plays', 'comments']);
        
        if ($filter === 'mine') 
        {
            $query->where('user_id', $user->id);
        }
        elseif ($filter === 'public') {
            $query->where('visibility', 'public');
        }
        elseif ($filter === 'private') 
        {
            $query->where('visibility', 'private')
                  ->where('user_id', $user->id);
        }
        elseif ($filter === 'shared') 
        {
            $query->where('visibility', 'private')
                  ->whereHas('accesses', fn($q) => $q->where('user_id', $user->id));
        }
        else 
        {
            $query->where('visibility', 'public')
                ->orWhere('user_id', $user->id)
                ->orWhereHas('accesses', fn($q) => $q->where('user_id', $user->id));
        }

        if ($search) 
        {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('owner', fn ($q2) =>
                    $q2->where('artist_name', 'like', "%{$search}%")
                );
            }); 
        }

        if ($sort === 'popularity') 
        {
            $query->orderByDesc('play_count');
        } 
        elseif ($sort === 'newest') 
        {
            $query->latest();
        }
        elseif ($sort === 'oldest') 
        {
            $query->oldest();
        }
        elseif ($sort === 'reactions') 
        {
            $query->withCount('reactions')->orderByDesc('reactions_count');
        }
        else 
        {
            $query->latest();
        }
        
        $tracks = $query->paginate(5)->withQueryString();
        
        $recentComments = $user->comments()
            ->with('track')
            ->latest()
            ->limit(5)
            ->get();

        $recentReactions = $user->reactions()
            ->with('track')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('tracks', 'user', 'filter', 'search', 'sort', 'recentComments', 'recentReactions'));
    }

    public function show(Track $track)
    {
        $user = auth()->user();
        
        if ($track->visibility === 'private'
            && $track->user_id !== $user->id
            && !$track->accesses()->where('user_id', $user->id)->exists()
            ) 
        {
                abort(403);
        }
        $track->load(['reactions', 'plays', 'comments.user']);
        return view('tracks.show', compact('track'));
    }

    public function recordPlay(Track $track)
    {
        TrackPlay::create([
            'track_id' => $track->id,
            'user_id' => Auth::id(),
        ]);

        $track->increment('play_count');
        return redirect()->route('tracks.show', [
            'track' => $track->id,
            'play' => 1
        ]);
    }

    public function addReaction(Request $request, Track $track)
    {
        $request->validate(['type' => 'required|in:like,dislike,laugh,wow,sad']);

        Reaction::updateOrCreate(
            ['user_id' => Auth::id(), 'track_id' => $track->id],
            ['type' => $request->type]
        );

        return back();
    }

    public function addComment(Request $request, Track $track)
    {
        $request->validate(['body' => 'required|string|max:500']);

        Comment::create([
            'user_id' => Auth::id(),
            'track_id' => $track->id,
            'body' => $request->body,
            'timestamp_ms' => $request->input('timestamp_ms'),
        ]);

        return back();
    }

    public function create()
    {
        return view('tracks.upload');
    }

    public function upload(Request $request) 
    {
        $request->validate([
        'title' => ['required','string','max:255'],
        'audio' => ['required','file','mimetypes:audio/mpeg,audio/mp3,audio/wav','max:25600'],
        'cover_image' => ['nullable', 'image', 'max:2048'],
        'visibility' => ['required','in:public,private'],
        ]);

        $path = $request->file('audio')->store('tracks', 'public');
        
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        Track::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'audio_file_path' => $path,
            'cover_image_path' => $coverPath,
            'visibility' => $request->visibility,
            'play_count' => 0,
        ]);

        return redirect()->route('dashboard')->with('status','Track uploaded!');
    }

    private function canManage(Track $track): void
    {
        $user = auth()->user();
        if ($track->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }
    }

    public function edit(Track $track)
    {
        $this->canManage($track);

        return view('tracks.edit', compact('track'));
    }

    public function update(Request $request, Track $track)
    {
        $this->canManage($track);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'visibility' => ['required', 'in:public,private'],
            'audio'=> ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp3,audio/wav', 'max:25600'],
            'cover_image' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('audio')) {
            if ($track->audio_file_path) 
            {
                Storage::disk('public')->delete($track->audio_file_path);
            }
            $path = $request->file('audio')->store('tracks', 'public');
            $track->audio_file_path = $path;
        }

        if ($request->hasFile('cover_image')) 
        {
            if ($track->cover_image_path) 
            {
                Storage::disk('public')->delete($track->cover_image_path);
            }
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $track->cover_image_path = $coverPath;
        }

        $track->title = $data['title'];
        $track->visibility = $data['visibility'];

        $track->save();

        return redirect()
            ->route('tracks.show', $track)
            ->with('status', 'Track updated.');
    }

    public function destroy(Track $track)
    {
        $this->canManage($track);

        $track->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Track deleted.');
    }

}

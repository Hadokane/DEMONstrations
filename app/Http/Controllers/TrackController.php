<?php

namespace App\Http\Controllers;

use App\Models\{Track, TrackPlay, Reaction, Comment, TrackAccess, User, Notification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 

class TrackController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $recentTracks = $user->tracks()
            ->withCount(['plays', 'reactions', 'comments'])
            ->latest()
            ->limit(4)
            ->get();

        $recentSharedTracks = Track::query()
            ->where('visibility', 'private')
            ->whereHas('accesses', fn ($q) => $q->where('user_id', $user->id))
            ->with('owner')
            ->latest()
            ->limit(3)
            ->get();

        $trendingTracks = Track::where('visibility', 'public')
            ->with('owner')
            ->orderByDesc('play_count')
            ->limit(3)
            ->get();
            
        $recentComments = $user->comments()
            ->with('track')
            ->latest()
            ->limit(3)
            ->get();

        $recentReactions = $user->reactions()
            ->with('track')
            ->latest()
            ->limit(3)
            ->get();

        return view('dashboard', compact( 'user', 'recentTracks',  'recentSharedTracks', 'trendingTracks',  'recentComments', 'recentReactions'));
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

        $reaction = Reaction::updateOrCreate(
            ['user_id' => Auth::id(), 'track_id' => $track->id],
            ['type' => $request->type]
        );

            $owner = $track->owner;

        if ($owner && $owner->id !== Auth::id()) 
        {
            $reactionsMapping = [
                'like'    => '👍',
                'dislike' => '👎',
                'laugh'   => '😂',
                'wow'     => '😮',
                'sad'     => '😢',
            ];

            $reactionType = $reactionsMapping[$reaction->type] ?? '?';

            Notification::create([
                'user_id' => $owner->id,
                'type'    => 'reaction',
                'title'   => auth()->user()->artist_name." reacted {$reactionType} to your track",
                'body'    => $track->title,
                'link'    => route('tracks.show', $track),
            ]);
        }

        return back();
    }

    public function addComment(Request $request, Track $track)
    {
        $request->validate(['body' => 'required|string|max:500']);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'track_id' => $track->id,
            'body' => $request->body,
            'timestamp_ms' => $request->input('timestamp_ms'),
        ]);

        $owner = $track->owner;

        if ($owner && $owner->id !== Auth::id()) {
            Notification::create([
                'user_id' => $owner->id,
                'type'    => 'comment',
                'title'   => auth()->user()->artist_name.' commented on your track',
                'body'    => '"'.$track->title.'": '.$comment->body,
                'link'    => route('tracks.show', $track).'#comments',
            ]);
        }

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

    public function trending(Request $request)
    {
        $search = $request->query('search');

        $query = Track::where('visibility', 'public')
            ->with('owner');

        if ($search) 
        {
            $query->where(function ($q) use ($search) 
            {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('owner', fn ($q2) =>
                        $q2->where('artist_name', 'like', "%{$search}%")
                    );
            });
        }

        $tracks = $query->orderByDesc('play_count')
            ->paginate(10)
            ->withQueryString();

        return view('tracks.trending', compact('tracks', 'search'));
    }

    public function share(Request $request, Track $track)
    {
        $this->canManage($track);

        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $userToShare = User::where('email', $data['email'])->first();

        if ($userToShare->id === $track->user_id) 
        {
            return back()->withErrors([
                'email' => 'You already have access to this track.',
            ]);
        }

        TrackAccess::firstOrCreate([
            'track_id' => $track->id,
            'user_id'  => $userToShare->id,
        ]);

        return back()->with('status', 'Access granted to '.$userToShare->artist_name.' ('.$userToShare->email.').');
    }

    public function unshare(Track $track, User $user)
    {
        $this->canManage($track);

        $track->accesses()
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('status', 'Access revoked for '.$user->artist_name.'.');
    }
}

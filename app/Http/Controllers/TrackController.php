<?php

namespace App\Http\Controllers;

use App\Models\{Track, TrackPlay, Reaction, Comment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function index()
    {
        // show all tracks that the user can access
        $user = Auth::user();

        $tracks = Track::query()
            ->where('visibility', 'public')
            ->orWhere('user_id', $user->id)
            ->orWhereHas('accesses', fn($q) => $q->where('user_id', $user->id))
            ->with(['reactions', 'plays', 'comments'])
            ->get();

        return view('dashboard', compact('tracks', 'user'));
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
        return back();
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

    public function upload(Request $request) 
    {
        $request->validate([
        'title'      => ['required','string','max:255'],
        'artist'     => ['nullable','string','max:255'],
        'audio'      => ['required','file','mimetypes:audio/mpeg,audio/mp3,audio/wav','max:25600'],
        'visibility' => ['required','in:public,private'],
        ]);

        $path = $request->file('audio')->store('tracks', 'public');

        Track::create([
            'user_id'         => auth()->id(),
            'title'           => $request->title,
            'artist'          => $request->artist,
            'audio_file_path' => $path,
            'visibility'      => $request->visibility,
            'play_count'      => 0,
        ]);

        return redirect()->route('dashboard')->with('status','Track uploaded!');
    }

}

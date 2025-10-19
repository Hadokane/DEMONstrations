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
}

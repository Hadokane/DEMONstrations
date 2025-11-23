<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function destroy(Track $track)
    {

        $actor = Auth::user();
        if ($actor->is_admin && $actor->id !== $track->user_id) {
            Notification::create([
                'user_id' => $track->user_id,
                'type'    => 'track_delete',
                'title'   => "{$actor->artist_name} deleted one of your tracks",
                'body'    => $track->title,
                'link'    => null,
            ]);
        }
        
        $track->delete();
        return back()->with('status', 'Track deleted.');
    }
}

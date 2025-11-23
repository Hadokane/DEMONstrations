<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;

class TrackController extends Controller
{
    public function destroy(Track $track)
    {
        $track->delete();

        return back()->with('status', 'Track deleted.');
    }
}

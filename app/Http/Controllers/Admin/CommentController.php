<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function destroy(Comment $comment)
    {
        $actor = Auth::user();
        if ($actor->is_admin && $actor->id !== $comment->user_id) 
        {
            Notification::create([
                'user_id' => $comment->user_id,
                'type'    => 'comment_deleted',
                'title'   => "{$actor->artist_name} removed one of your comments",
                'body'    => 'On track: '.$comment->track->title,
                'link'    => route('tracks.show', $comment->track),
            ]);
        }
        
        $comment->delete();
        
        return back()->with('status', 'Comment deleted.');
    }
}

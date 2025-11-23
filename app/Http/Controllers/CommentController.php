<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function destroy(Comment $comment)
    {
        $user = request()->user();

        if ($comment->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        $comment->delete();

        return back()->with('status', 'Comment deleted.');
    }
}

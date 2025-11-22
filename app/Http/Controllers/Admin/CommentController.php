<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('status', 'Comment deleted.');
    }
}

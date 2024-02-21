<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Comment;
use App\Models\CommentsLike;

//use Carbon\Carbon;

class CommentController extends Controller
{
    
    public function comments(Timeline $timeline, Event $event, Request $request) 
    {

        if ($request->ajax()){

            if ($event->id) {
                $comments = $event->comments;
            } else {
                $comments = $timeline->comments;
            }

            $comments_html = view('layouts.timeline.ajax.comments', ['comments' => $comments])->render();
            $comments_count = $comments->count();

            return response()->json(array(
                'success' => true,
                'comments_html' => $comments_html,
                'comments_count' => $comments_count
            ));

        }

    }

    public function like(Timeline $timeline, Comment $comment, Request $request) 
    {

        if ($request->ajax()){

            if (auth()->check()) {

                // check if already liked
                if ($comment->likedByUser()) {

                    $comment->likes()->where('user_id', auth()->id())->where('comment_id', $comment->id)->delete();
                    $like = false;

                } else {

                    CommentsLike::create(['comment_id' => $comment->id, 'user_id' => auth()->id()]);
                    $like = true;
                    
                }

                return response()->json(array(
                    'success' => true,
                    'increment' => $like,
                    'count' => convert($comment->likesCount()),
                ));

            } else {

                // show modal
                return response()->json(array(
                    'success' => false
                ));

            }

        }

    }

}

function convert($n) {
    if ($n < 1000) {
        $n_format = number_format($n);
    } else if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n / 1000, 3) . 'k';
    } else {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, 3) . 'M';
    }
    return $n_format;
}
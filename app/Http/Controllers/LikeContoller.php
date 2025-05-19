<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeContoller extends Controller
{
    //get all likes of a post
    public function likeOrUnlike($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $like = $post->likes()->where('user_id', auth()->id())->first();

        if (!$like) {
            $like::create(
                [
                    'user_id' => auth()->id(),
                    'post_id' => $id,
                ]
            );
            return response(['message' => 'Liked.'], 200);
        }

        $like->delete();
        return response(['message' => 'Disliked.'], 200);    }
}

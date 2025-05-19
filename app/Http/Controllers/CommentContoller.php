<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentContoller extends Controller
{
    //get all comments of a post
    public function index($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response(['post' => $post()->comments()->with(['user:id', 'name', 'image'])->get()], 200);
    }

    //insert comment
    public function store(Request $request, $id)
    {

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $attrs = $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $id,
            'comment' => $attrs['comment'],
        ]);

        return response(['message' => 'Comment created.'], 200);
    }

    //update comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $attrs = $request->validate([
            'comment' => 'required|string',
        ]);

        $comment->update([
            'comment' => $attrs['comment'],
        ]);

        return response(['message' => 'Comment updated.'], 200);
    }

    //delete comment
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response(['message' => 'Comment deleted.'], 200);
    }
}

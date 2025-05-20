<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;

class PostController extends Controller
{
    //get all posts
    public function index()
    {

        $posts = Post::orderBy('created_at','desc')
                    ->with('user:id,name,image')
                    ->withCount('comments','likes')
                    ->with('likes', function ($like) {
                        $like->where('user_id', auth()->id())
                            ->select('id', 'post_id', 'user_id')
                            ->get();
                    })
                    ->get();

        return response(['posts' => $posts], 200);
    }

    //get single post by id
    public function show($id)
{
    $post = Post::where('id', $id)
        ->with(['user:id,name,image'])
        ->withCount('comments','likes')
        ->first();

    if (!$post) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    return response(['post' => $post], 200);
}
    // {
    //     // $post = Post::with('user', 'comments', 'likes')->findOrFail($id);
    //     // $post = Post::where('id', $id)
    //     //     ->with(['user:id,name,image'])
    //     //     ->withCount('comments','likes')
    //     //     ->first();
    //     // return response()->json($post);

    //     return respose(['post' => PostArr::where('id', $id)->withCount('comments','likes')->get()], 200);
    // }

    // insert post
    public function store(Request $request)
    {
        $attrs = $request->validate([
            // 'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'user_id' => auth()->id(),
            // 'title' => $request->title,
            'body' => $attrs['body'],
            'image' => $image,
        ]);

        return response(['message' => 'Post added.','post' => $post],200);
    }

    // update post by id
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Permission denied!'], 403);
        }

        $attrs = $request->validate([
            'body' => 'required|string',
        ]);

        $post->update(['body' => $attrs['body']]);

        return response(['message' => 'Post updated.','post' => $post],200);
    }

    // delete post by id
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Permission denied'], 403);
        }

        $post->likes()->delete();
        $post->comments()->delete();
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}

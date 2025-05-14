<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Auth routes

Route::group(['middleware' => ['auth:sanctum']], function () {

    // user
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // posts
    Route::get('/posts', [PostController::class, 'index']);     // Get all posts
    Route::post('/posts', [PostController::class, 'store']);            // Create a new post
    Route::get('/posts/{id}', [PostController::class, 'show']);         // Read a single post
    Route::put('/posts/{id}', [PostController::class, 'update']);       // Update a post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);   // Delete a post
    // comments
    Route::get('/posts/{postId}/comments', [CommentController::class, 'index']); // Get all comments for a post
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store']); // Create a new comment for a post
    // Route::get('/posts/{postId}/comments/{id}', [CommentController::class, 'show']); // Read a single comment
    Route::put('/comments/{id}', [CommentController::class, 'update']); // Update a comment
    Route::delete('comments/{id}', [CommentController::class, 'destroy']); // Delete a comment
    // likes
    Route::post('/posts/{postId}/likes', [LikeController::class, 'likeOrUnlike']); // Like or Dislike a post
    // Route::post('/posts/{postId}/like', [LikeController::class, 'like']); // Like a post
    // Route::post('/posts/{postId}/unlike', [LikeController::class, 'unlike']); // Unlike a post
    // Route::get('/posts/{postId}/likes', [LikeController::class, 'index']); // Get all likes for a post
    // Route::get('/posts/{postId}/likes/count', [LikeController::class, 'count']); // Get like count for a post
    // Route::get('/posts/{postId}/likes/check', [LikeController::class, 'check']); // Check if user liked a post
});


// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/user', [AuthController::class, 'user']);
// });

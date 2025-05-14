<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // https://laravel.com/docs/9.x/sanctum
    // https://www.youtube.com/watch?v=099M0k_qoXA&list=PL0qWGthGFUCjFDgYI2k_-TqMNA7925c1s&index=3

    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // return response()->json(['message' => 'User registered successfully'], 201);

        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ], 200);

    }

    /**
     * Login user and return token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        // ]);

        // if (Auth::attempt($request->only('email', 'password'))) {
        //     $user = Auth::user();
        //     $token = $user->createToken('secret')->plainTextToken;

        //     return response()->json([
        //         'user' => $user,
        //         'token' => $token
        //     ], 200);
        // }

        //    return response()->json(['message' => 'Invalid credentials.'], 403);

        $attrs = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if (!Auth::attempt($attrs)) {
            return response(['message' => 'Invalid credentials.'], 403);
        }

        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }

    /**
     * Logout user (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response(['message' => 'Logged out successfully'], 200);
    }


    /**
     * Get authenticated user
     *
     */
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }
}

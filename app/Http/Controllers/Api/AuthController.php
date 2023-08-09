<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('logout');
    }

    public function register(StoreUserRequest $request)
    {
        // Validate user request
        $request->validated($request->only('name', 'username', 'email', 'password', 'avatar'));

        // Store user in database
        $user = User::create([
            "name" => $request->name,
            "username" => $request->username,
            "email" => $request->email,
            "password" => $request->password,
        ]);

        // Generate accessible token
        $tokenName = "Token of $user->name";
        $tokenExpTime = now()->addMinutes(env('SESSION_LIFETIME', 60));
        $token = $user->createToken($tokenName, ["*"], $tokenExpTime)->plainTextToken;

        // Cookie
        $cookie = cookie(
            'token',
            $token,
            env('SESSION_LIFETIME', 60),
            "/",
            null,
            false,
            true
        );

        // Response with Http-Only Cookie token
        return response()->json([
            "status" => 201,
            "statusText" => "Created",
            "message" => "Registered successful",
            "token" => $token,
            "expires_at" => $tokenExpTime->diffForHumans()
        ], 201)->withCookie($cookie);
    }

    public function login(LoginUserRequest $request)
    {
        // Validate user request
        $request->validated($request->only('name', 'email'));

        // Check user with email and password
        if(!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                "status" => 422,
                "statusText" => "Unprocessable Content",
                "message" => "Wrong credentials",
                "errors" => ["password" => ["Wrong password"]]
            ], 422);
        }

        // User
        $user = User::where("email", $request->email)->first();

        // Generate Token
        $tokenName = "Token of $user->name";
        $tokenExpTime = now()->addMinutes(env('SESSION_LIFETIME', 60));
        $token = $user->createToken($tokenName, ["*"], $tokenExpTime)->plainTextToken;

        // Cookie
        $cookie = cookie(
            'token',
            $token,
            env('SESSION_LIFETIME', 60),
            "/",
            null,
            false,
            true
        );

        // Response with Http-Only Cookie token
        return response()->json([
            "status" => 200,
            "statusText" => "OK",
            "message" => "Logged in successful",
            "token" => $token,
            "expires_at" => $tokenExpTime->diffForHumans()
        ], 200)->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        // Delete current user token
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            "status" => 200,
            "statusText" => "OK",
            "message" => "Logged out successful"
        ], 200)->withCookie(Cookie::forget('token'));
    }
}

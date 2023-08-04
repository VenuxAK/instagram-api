<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return [
        "user" => new UserResource($request->user())
    ];
});

Route::get('/get-token', function(Request $request) {
    $token = $request->cookie('token');

    if($token) {
        return response()->json([
            "status" => 200,
            "statusText" => "OK",
            "token" => $token,
        ], 200);
    }
    return response()->json([
        "message" => "Please login first to make this request"
    ]);
});

Route::post('/register', [AuthController::class, "register"]);
Route::post('/login', [AuthController::class, "login"]);
Route::post('/logout', [AuthController::class, "logout"]);


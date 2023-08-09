<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Resources\FeedResource;
use App\Http\Resources\UserResource;
use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return [
        "user" => new UserResource($request->user())
    ];
});
Route::middleware('auth:sanctum')->get('/user/feeds', function(Request $request) {
    $feeds = Feed::latest()->where("user_id", auth()->id())->get();
    return [
        // "feeds" => $feeds
        "feeds" => FeedResource::collection($feeds)
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

Route::prefix('v1')->group(function () {
    Route::resource('feeds', FeedController::class);
});

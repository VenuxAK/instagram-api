<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedRequest;
use App\Http\Resources\FeedResource;
use App\Models\Feed;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FeedController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "status" => 200,
            "statusText" => "OK",
            "feeds" => FeedResource::collection(Feed::orderBy('id', 'desc')->get())
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            "status" => 404
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedRequest $request)
    {
        $request->validated($request->only('message', 'image'));

        $path = $request->file('image')->store('posts');

        $feed = Feed::create([
            "message" => $request->message ?? NULL,
            "image" => $path,
            "user_id" => auth()->user()->id
        ]);

        return response()->json([
            "status" => 201,
            "statusText" => "Created",
            "feed" => new FeedResource($feed)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $feed = Feed::findOrFail($id);
            return response()->json([
                "status" => 200,
                "statusText" => "OK",
                "feed" => new FeedResource($feed)
            ], 200);
        } catch(ModelNotFoundException $err) {
            return response()->json([
                "status" => 404,
                "statusText" => "Not Found",
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json([
            "status" => 404
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $feed = Feed::findOrFail($id);
            if($this->isAuthorized($feed)) {
                return $this->isAuthorized($feed);
            }
            $request->validate([
                "message" => ["string"]
            ]);
            $feed->update([
                "message" => $request->message
            ]);
            return response()->json([
                "status" => 200,
                "statusText" => "OK",
                "feed" => new FeedResource($feed)
            ], 200);
        } catch(ModelNotFoundException $err) {
            return response()->json([
                "status" => 404,
                "statusText" => "Not Found",
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $feed = Feed::findOrFail($id);
            if($this->isAuthorized($feed)) {
                return $this->isAuthorized($feed);
            }
            $feed->delete();
            return response()->json([
                "status" => 204,
                "statusText" => "No Content",
            ], 204);
        } catch(ModelNotFoundException $err) {
            return response()->json([
                "status" => 404,
                "statusText" => "Not Found",
            ], 404);
        }
    }

    protected function isAuthorized ($feed)
    {
        if($feed->user_id !== auth()->user()->id)
        {
            return response()->json([
                "status" => 401,
                "statusText" => "Unauthorized",
                "message" => "Your'e not authorized to make this request"
            ], 401);
        }
    }
}

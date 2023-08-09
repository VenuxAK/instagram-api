<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "message" => $this->message,
            "image" => asset('/storage/' . $this->image),
            "created_at" => $this->created_at->diffForHumans(),
            "user" => [
                "id" => $this->user_id,
                "name" => $this->author->name,
                "username" => $this->author->username,
                "email" => $this->author->email,
            ]
        ];
    }
}

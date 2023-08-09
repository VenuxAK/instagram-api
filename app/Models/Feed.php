<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    protected $guarded = ["id", "timestamps"];
    protected $fillable = ["message", "image", "user_id"];
    protected $with = ["author"];

    public function author()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}

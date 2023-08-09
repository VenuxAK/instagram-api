<?php

namespace Database\Seeders;

use App\Models\Feed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feed::factory(1)->create([
            "image" => "posts/9.jpg",
            "user_id" => 1
        ]);
        // Feed::factory(3)->create([
        //     "image" => "/posts/4.jpg"
        //     ,"user_id" => 2
        // ]);
        // Feed::factory(1)->create([
        //     "image" => "/posts/8.jpg"
        //     ,"user_id" => 3
        // ]);
    }
}

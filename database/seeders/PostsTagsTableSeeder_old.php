<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $postIds = Post::pluck('id')->toArray();
        foreach ($postIds as $postId) {
            $tagIds = Tag::inRandomOrder()->take(3)->pluck('id')->toArray();
            foreach ($tagIds as $tagId) {
                DB::table('posts_tags')->insert([
                    'post_id' => $postId,
                    'tag_id' => $tagId,
                ]);
            }
        }
    }
}

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
        $postIds = Post::typePost()->pluck('id')->toArray();
        $data = [];
        foreach($postIds as $postId){
            $tagIds = Tag::inRandomOrder()->take(3)->pluck('id')->toArray();
            foreach($tagIds as $tagId){
                $data[] = [
                    'post_id' => $postId,
                    'tag_id' => $tagId,
                ];
            }
        }

        $dataChunks = array_chunk($data, 1000);
        foreach($dataChunks as $dataChunck){
            DB::table('posts_tags')->insert($dataChunck);
        }
    }
}

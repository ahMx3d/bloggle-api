<?php

namespace App\Observers;

use App\Models\Tag;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    private $post_service;

    public function __construct()
    {
        $this->post_service = new PostService();
    }
    /**
     * Handle the post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        try {
            if(count(request()->tags)){
                $tags = [];
                foreach (request()->tags as $tag) {
                    $tag = Tag::firstOrCreate(
                        ['id'   => $tag],
                        ['name' => $tag],
                    );
                    $tags[] = $tag->id;
                }
                $post->tags()->sync($tags);
            }
            if (request()->images && count(request()->images) > 0) {
                $images = images_upload(
                    request()->images,
                    $post->slug,
                    'posts'
                );
                $images = $this->post_service->map_image_attributes_to_array(
                    $images,
                    $post
                );
                $post->media()->insert($images);
            }
            if ($post->status == 'Active' and !$post->post_type == 'page') {
                Cache::forget('global_tags');
                Cache::forget('recent_posts');
            }
        } catch (\Exception $err) {
            images_remove($images);
            throw new \Exception($err);
        }
    }

    /**
     * Handle the post "updated" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        if ($post->status == 'Active') Cache::forget('recent_posts');
    }

    /**
     * Handle the post "deleting" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleting(Post $post)
    {
        try {
            if($post->media->count() > 0) images_remove($post->media);
            if ($post->status == 'Active') Cache::forget('recent_posts');
        } catch (\Exception $err) {
            throw new \Exception($err);
        }
    }

    /**
     * Handle the post "restored" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }

    /**
     * Handle the post "force deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        //
    }
}

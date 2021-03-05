<?php

namespace App\Listeners;

use Exception;
use App\Events\PostUpdated;
use App\Services\PostService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePostMedia
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $post_service;
    public function __construct()
    {
        $this->post_service = new PostService();
    }

    /**
     * Handle the event.
     *
     * @param  PostUpdated  $event
     * @return void
     */
    public function handle(PostUpdated $event)
    {
        $this->media_update($event->post);
    }

    private function media_update($post)
    {
        try {
            if (request()->images && count(request()->images) > 0) {
                // dispatch(new UploadPostMediaJob(
                //     // request()->images,
                //     request()->all(),
                //     $post
                // ));
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
                if ($post->status == 1) Cache::forget('recent_posts');
            }
        } catch (\Exception $err) {
            images_remove($images);
            throw new \Exception($err);
        }
    }
}

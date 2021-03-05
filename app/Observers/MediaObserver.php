<?php

namespace App\Observers;

use App\Models\PostMedia;

class MediaObserver
{
    /**
     * Handle the post media "created" event.
     *
     * @param  \App\Models\PostMedia  $postMedia
     * @return void
     */
    public function created(PostMedia $postMedia)
    {
        //
    }

    /**
     * Handle the post media "updated" event.
     *
     * @param  \App\Models\PostMedia  $postMedia
     * @return void
     */
    public function updated(PostMedia $postMedia)
    {
        //
    }

    /**
     * Handle the post media "deleted" event.
     *
     * @param  \App\Models\PostMedia  $postMedia
     * @return void
     */
    public function deleted(PostMedia $postMedia)
    {
        try {
            image_remove("assets/posts/{$postMedia->file_name}");
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * Handle the post media "restored" event.
     *
     * @param  \App\Models\PostMedia  $postMedia
     * @return void
     */
    public function restored(PostMedia $postMedia)
    {
        //
    }

    /**
     * Handle the post media "force deleted" event.
     *
     * @param  \App\Models\PostMedia  $postMedia
     * @return void
     */
    public function forceDeleted(PostMedia $postMedia)
    {
        //
    }
}

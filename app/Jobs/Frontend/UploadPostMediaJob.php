<?php

namespace App\Jobs\Frontend;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadPostMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    private $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request,Post $post)
    {
        $this->request = $request;
        $this->post    = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $images = images_upload(
            $this->request['images'],
            $this->post->slug,
            'posts'
        );
        // foreach ($images as $image) {
        //     $this->post->media()->create($image);
        // }
        $this->post->media()->insert($images);
    }
}

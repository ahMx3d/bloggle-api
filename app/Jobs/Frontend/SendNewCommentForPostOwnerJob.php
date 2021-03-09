<?php

namespace App\Jobs\Frontend;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Notifications\Frontend\NewCommentForPostOwnerNotify;

class SendNewCommentForPostOwnerJob implements ShouldQueue, ShouldBroadcast
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $comment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // if(auth()->guest() || auth()->id() != $this->comment->post->user_id)
            $this->comment->post->user->notify(
                new NewCommentForPostOwnerNotify($this->comment)
            );
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}

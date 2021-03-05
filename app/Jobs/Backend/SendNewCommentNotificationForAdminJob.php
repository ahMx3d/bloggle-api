<?php

namespace App\Jobs\Backend;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\Backend\NewCommentForAdminNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Notifications\Frontend\NewCommentForPostOwnerNotify;

class SendNewCommentNotificationForAdminJob implements ShouldQueue, ShouldBroadcast
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
        User::whereHas('roles', function($query){
            $query->whereIn('name', ['admin', 'editor']);
        })->each(function($admin, $key){
            $admin->notify(new NewCommentForAdminNotify($this->comment));
        });
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

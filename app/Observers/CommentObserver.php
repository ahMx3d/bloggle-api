<?php

namespace App\Observers;

use App\Jobs\Backend\SendNewCommentNotificationForAdminJob;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use App\Jobs\Frontend\SendNewCommentForPostOwnerJob;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        if($comment->status == 'Active') Cache::forget('recent_comments');
        // Handle Notifications queue job.
        dispatch(new SendNewCommentNotificationForAdminJob($comment));
        if(auth()->guest() || auth()->id() != $comment->post->user_id){
            dispatch(new SendNewCommentForPostOwnerJob($comment));
        }
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        Cache::forget('recent_comments');
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        if($comment->status == 'Active') Cache::forget('recent_comments');
    }

    /**
     * Handle the comment "restored" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "force deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }
}

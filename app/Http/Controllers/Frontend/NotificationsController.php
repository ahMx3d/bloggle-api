<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function get()
    {
        $user = auth()->user();
        return [
            'read'      => $user->readNotifications,
            'unread'    => $user->unreadNotifications,
            'user_type' => $user->roles->first()->name,
        ];
    }

    public function mark_as_read(Request $request)
    {
        return auth()->user()->notifications->where(
            'id',
            $request->id
        )->markAsRead();
    }

    public function mark_as_read_and_redirect($id)
    {
        $user = auth()->user();
        $notification = $user->notifications->where(
            'id',
            $id
        )->first();
        $notification->markAsRead();
        
        if($user->roles->first()->name == 'user'){
            if ($notification->type == 'App\Notifications\Frontend\NewCommentForPostOwnerNotify') {
                return redirect_to(
                    'frontend.posts.show',
                    $notification->data['post_slug']
                    // 'user.comment.edit',
                    // $notification->data['id']
                );
            } else {
                return redirect()->back();
            }

        }
    }
}

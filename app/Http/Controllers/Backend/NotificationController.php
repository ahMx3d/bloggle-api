<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
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

    public function markAsRead(Request $request)
    {
        return auth()->user()->notifications->where(
            'id',
            $request->id
        )->markAsRead();
    }
}

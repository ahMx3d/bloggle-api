<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        $user = auth()->user();
        return [
            'read'      => $user->readNotifications,
            'unread'    => $user->unreadNotifications,
            'user_type' => $user->roles->first()->name,
        ];
    }
    public function store(Request $request)
    {
            return auth()->user()->notifications
                ->where('id', $request->id)
                ->markAsRead();
    }
}

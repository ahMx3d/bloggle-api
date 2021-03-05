<?php

namespace App\Http\Livewire\Backend;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Livewire\Component;

class Statistics extends Component
{
    public function render()
    {
        $usersCount = User::whereHas('roles', function($query){
            $query->where('name', 'user');
        })->active()->count();
        $activePostsCount    = Post::typePost()->active()->count();
        $pendingPostsCount   = Post::typePost()->pending()->count();
        $activeCommentsCount = Comment::active()->count();

        return view('livewire.backend.statistics',[
            'usersCount'          => $usersCount,
            'activePostsCount'    => $activePostsCount,
            'pendingPostsCount'   => $pendingPostsCount,
            'activeCommentsCount' => $activeCommentsCount
        ]);
    }
}

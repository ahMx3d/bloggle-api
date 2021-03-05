<?php

namespace App\Http\Livewire\Backend;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class Latest extends Component
{
    public function render()
    {
        $posts    = Post::typePost()->withCount('comments')->latest()->take(5)->get();
        $comments = Comment::latest()->take(5)->get();
        return view('livewire.backend.latest',[
            'posts'    => $posts,
            'comments' => $comments
        ]);
    }
}

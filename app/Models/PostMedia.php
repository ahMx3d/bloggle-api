<?php

namespace App\Models;

use App\Observers\MediaObserver;
use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    //
    protected $guarded = [];

    /**
     * Register Post Observer
     *
     * @return void
     */
    protected static function boot(){
		parent::boot();
		self::observe(MediaObserver::class);
	}

    /**
     * Get the user image or default image from server.
     *
     * @return string
     */
    public function postImages():string
    {
        return ((!$this->file_name)
        ? asset('assets/posts/default.jpg')
        : asset("assets/posts/{$this->file_name}"));
    }

    /**
     * The Media Post Relationship
     * Each media belongs to a post.
     *
     * @return object
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

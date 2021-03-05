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

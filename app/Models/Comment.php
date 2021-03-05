<?php

namespace App\Models;

use App\Observers\CommentObserver;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Comment extends Model
{
    use SearchableTrait;  // Search Box Package Trait.

    protected $guarded = [];
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'comments.name'       => 10,
            'comments.email'      => 10,
            'comments.url'        => 10,
            'comments.ip_address' => 10,
            'comments.comment'    => 10,
        ]
    ];

    /**
     * The Comment observer registration.
     *
     * @return void
     */
    protected static function boot(){
		parent::boot();
		self::observe(CommentObserver::class);
	}

    /**
     * Get the comment's status.
     *
     * @param  int  $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return ($value == 1)? 'Active': 'Pending';
    }

    /**
     * Scope a query to only include active comments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to order comments DESC.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderDesc($query)
    {
        return $query->orderBy(
            'id',
            'DESC'
        );
    }

    /**
     * The Comment Post Relationship
     * Each comment belongs to a post.
     *
     * @return object
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

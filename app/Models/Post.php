<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\PostObserver;
use Cviebrock\EloquentSluggable\Sluggable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Post extends Model
{
    use
        Sluggable,  // SEO Friendly Package Trait.
        SearchableTrait;  // Search Box Package Trait.

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
            'posts.title'       => 10,
            'posts.description' => 10
        ]
    ];

    /**
     * The Sluggable Trait Method Implementation.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Register Post Observer
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(PostObserver::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the post's status in numbers.
     * 0 = Pending
     * 1 = Active
     *
     * @param  int  $value
     * @return string
     */
    public function status()
    {
        return ($this->status == 'Active') ? 1 : 0;
    }

    /**
     * Get the post's status.
     *
     * @param  int  $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return ($value == 1) ? 'Active' : 'Pending';
    }

    /**
     * Scope a query to only select specific attributes of posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelection($query)
    {
        return $query->select(
            'id',
            'title',
            'slug',
            'description',
            'created_at',
            'user_id',
            'category_id',
        );
    }

    /**
     * Scope a query to only include active posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include pending posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scope a query to only include type-post posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTypePost($query)
    {
        return $query->where('post_type', 'post');
    }

    /**
     * Scope a query to order posts DESC.
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
     * The Post Category Relationship
     * Each post belongs to a category.
     *
     * @return object
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The Post User Relationship
     * Each post belongs to a user.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post for the blog tags.
     *
     * @return object
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'posts_tags');
    }

    /**
     * The Post Comment Relationship
     * Each post has many comments.
     *
     * @return object
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The active comments count
     *
     * @return int
     */
    public function approvedCommentsCount():int
    {
        return ($this->hasMany(Comment::class)->whereStatus(1)->count())
            ? $this->hasMany(Comment::class)->whereStatus(1)->count()
            : 0;
    }

    /**
     * Get Comments status as text
     *
     * @return string
     */
    public function commentable():string
    {
        return (!$this->comment_able)
            ? 'no'
            : 'yes';
    }

    /**
     * Get Comments count
     *
     * @return int
     */
    public function commentsCount():int
    {
        return ($this->hasMany(Comment::class)->count())
            ? $this->hasMany(Comment::class)->count()
            : 0;
    }

    /**
     * The Post Comment Relationship
     * Each post has many comments.
     * Only approved comments
     *
     * @return object
     */
    public function approved_comments()
    {
        return $this->hasMany(Comment::class)->whereStatus(1);
    }

    /**
     * The Post Media Relationship
     * Each post has many media.
     *
     * @return object
     */
    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }
}

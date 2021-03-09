<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Category extends Model
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
            'categories.name' => 10,
            'categories.slug' => 10,
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
                'source' => 'name'
            ]
        ];
    }

    /**
     * Scope a query to only include active categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to order categories DESC.
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
     * Get The Post count
     *
     * @return int
     */
    public function postsCount():int
    {
        return ($count = $this->hasMany(Post::class)->typePost()->active()->count())
            ? $count
            : 0;
    }

    /**
     * The Category Post Relationship
     * Each category has many posts.
     *
     * @return object
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * The human readable status record.
     *
     * @return object
     */
    public function status()
    {
        return ($this->status)? 'Active': 'Pending';
    }
}

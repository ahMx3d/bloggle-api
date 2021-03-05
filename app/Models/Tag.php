<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory, Sluggable, SearchableTrait;

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
            'tags.name' => 10,
            'tags.slug' => 10
        ]
    ];

    /**
     * The Sluggable Trait Method Implementation.
     *
     * @return array
     */
    public function sluggable():array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Get the tag for the blog posts.
     *
     * @return object
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'posts_tags');
    }
}

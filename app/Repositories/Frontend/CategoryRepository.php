<?php
namespace App\Repositories\Frontend;

use App\Models\Category;
use App\Interfaces\Frontend\Repositories\ICategoryRepository;

class CategoryRepository implements ICategoryRepository
{
    private $category_model;    // Repository model.
    /**
     * Construct categories model
     *
     * @return void
     */
    public function __construct()
    {
        $this->category_model = Category::class;
    }

    /**
     * Get active user by slug or id.
     *
     * @param string|int $key
     * @return object
     */
    public function category_get_by_key($key)
    {
        return $this->category_model::whereSlug($key)->orWhere(
            'id',
            '=',
            $key,
        )->active()->first();
    }

    /**
     * Limit Descending ordered active categories.
     *
     * @param int $limitation_count
     * @return object
     */
    public function all_categories_get()
    {
        return $this->category_model::active()->orderDesc()->get();
    }

    /**
     * Pluck active categories.
     *
     * @return object
     */
    public function all_categories_pluck()
    {
        return $this->category_model::active()->pluck(
            'name',
            'id'
        );
    }
}


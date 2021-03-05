<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\Repositories\IPageRepository;
use App\Models\Page;

class PageRepository implements IPageRepository
{
    private $page_model;    // Repository model.
    /**
     * Construct pages model
     *
     * @return void
     */
    public function __construct()
    {
        $this->page_model = Page::class;
    }
    /**
     * Get page by slug.
     *
     * @param string $slug
     * @return object
     */
    public function page_type($slug)
    {
        return $this->page_model::with([
            'media' => function ($query){
                $query->select(
                    'id',
                    'file_name',
                    'post_id'
                );
            }
        ])->withCount(
            'media'
        )->typePage()->active()->whereSlug($slug)->selection()->first();
    }
}


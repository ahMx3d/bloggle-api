<?php

namespace App\Repositories\Frontend;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Interfaces\Frontend\Repositories\IPostRepository;

class PostRepository implements IPostRepository
{
    private $post_model;    // Repository model.
    /**
     * Construct posts model
     *
     * @return void
     */
    public function __construct()
    {
        $this->post_model = Post::class;
    }

    /**
     * Build Descending ordered active posts query which have category and user are active
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function posts_query_build()
    {
        return $this->post_model::with([
            'tags',
            'user' => function ($query){
                $query->select(
                    'id',
                    'name',
                    'username'
                );
            },
            'media' => function ($query){
                $query->select(
                    'id',
                    'file_name',
                    'post_id'
                );
            }
        ])->withCount('media')->whereHas('category', function ($query){
            $query->active();
        })->whereHas('user', function ($query){
            $query->active();
        })->typePost()->active()->selection()->orderDesc();
    }

    /**
     * Build Descending ordered active post query for selection by different constraints.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function posts_query_build_by()
    {
        return $this->post_model::with([
            'tags',
            'user' => function ($query){
                $query->select(
                    'id',
                    'name',
                    'username'
                );
            },
            'category' => function ($query){
                $query->select(
                    'id',
                    'name'
                );
            },
            'media' => function ($query){
                $query->select(
                    'id',
                    'file_name',
                    'post_id'
                );
            },
        ])->withCount(
            'approved_comments'
        )->typePost()->active()->selection()->orderDesc();
    }

    /**
     * Limit Descending ordered active posts which have category and user are active.
     *
     * @param int $limitation_count
     * @return object
     */
    public function all_posts_limit($limitation_count)
    {
        return $this->posts_query_build()->limit($limitation_count)->get();
    }

    /**
     * Pluck archived Descending ordered active posts.
     *
     * @return object
     */
    public function archived_posts_pluck()
    {
        return $this->post_model::active()->orderDesc()->select(
            DB::raw('Year(created_at) as year'),
            DB::raw('Month(created_at) as month')
        )->pluck('year', 'month')->sortKeysDesc();
    }

    /**
     * Paginate Descending ordered active posts which have category and user are active.
     *
     * @param int $pagination_count
     * @return object
     */
    public function all_posts_paginate($pagination_count)
    {
        return $this->posts_query_build()->paginate($pagination_count);
    }

    /**
     * Search all posts of one or multiple words of each post.
     * then Paginate Descending ordered active posts which have
     * category and user are active.
     *
     * @param Illuminate\Http\Request $request
     * @param int $pagination_count
     * @return object
     */
    public function posts_search($request, $pagination_count)
    {
        $keyword = ($request->filled('keyword'))? $request->keyword: null;
        $posts   = $this->posts_query_build();
        if($keyword) $posts = $posts->search(
            $keyword,
            null,
            true
        );
        return $posts->paginate($pagination_count);
    }

    /**
     * Get active post which have category and user are active by slug.
     *
     * @param string $slug
     * @return object
     */
    public function post_get_by_slug_with_relations($slug)
    {
        return $this->post_model::with([
            'tags',
            'user' => function ($query){
                $query->select(
                    'id',
                    'name',
                    'username'
                );
            },
            'category' => function ($query){
                $query->select(
                    'id',
                    'name'
                );
            },
            'media' => function ($query){
                $query->select(
                    'id',
                    'file_name',
                    'post_id'
                );
            },
            'approved_comments' => function ($query)
            {
                $query->select(
                    'id',
                    'name',
                    'comment',
                    'email',
                    'url',
                    'created_at',
                    'post_id',
                )->orderBy('id', 'DESC');
            }
        ])->withCount([
            'media',
            'approved_comments'
        ])->whereHas('category', function ($query){
            $query->active();
        })->whereHas('user', function ($query){
            $query->active();
        })->typePost()->active()->whereSlug($slug)->selection()->first();
    }

    /**
     * Get active posts which have category and user are active by slug.
     *
     * @param int $category_id
     * @param int $pagination_count
     * @return object
     */
    public function posts_get_by_category_with_relations(
        $category_id,
        $pagination_count
    )
    {
        return $this->posts_query_build_by()->whereCategoryId(
            $category_id
        )->paginate($pagination_count);
    }

    /**
     * Get active posts which have category and user are active by date.
     *
     * @param string $date
     * @param int $pagination_count
     * @return object
     */
    public function posts_get_by_date_with_relations($date, $pagination_count)
    {
        $date_arr = explode('-',$date);
        $month    = $date_arr[0];
        $year     = $date_arr[1];

        return $this->posts_query_build_by()->whereMonth(
            'created_at',
            $month
        )->whereYear(
            'created_at',
            $year
        )->paginate($pagination_count);
    }

    /**
     * Get active posts which have category and user are active by user id.
     *
     * @param int $user_id
     * @param int $pagination_count
     * @return object
     */
    public function posts_get_by_user_with_relations($user_id, $pagination_count)
    {
        return $this->posts_query_build_by()->whereUserId(
            $user_id
        )->paginate(
            $pagination_count
        );
    }

    /**
     * Get active post by slug.
     *
     * @param string $slug
     * @return object
     */
    public function post_get_by_slug($slug)
    {
        return $this->post_model::whereSlug(
            $slug
        )->typePost()->active()->first();
    }
}


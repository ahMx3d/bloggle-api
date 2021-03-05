<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Requests\Frontend\PostRequest;
use App\Interfaces\Frontend\Repositories\IPostRepository;
use App\Interfaces\Frontend\Repositories\IUserRepository;
use App\Interfaces\Frontend\Repositories\IAuthPostRepository;
use App\Interfaces\Frontend\Repositories\ICategoryRepository;
use Illuminate\Support\Facades\Cache;

class PostsController extends Controller
{
    private $post_repo;         // Posts repository.
    private $auth_post_repo;    // Authenticated Posts repository.
    private $category_repo;     // Categories repository.
    private $user_repo;         // Users repository.
    private $pagination_count;  // Global pagination count constant.

    /**
     * Construct posts repository interface.
     * Construct categories repository interface.
     * Construct users repository interface.
     * Construct posts pagination count constant.
     *
     * Construct post methods middleware.
     *
     * @param IPostRepository $post_repo
     * @param ICategoryRepository $category_repo
     * @param IUserRepository $user_repo
     * @return void
     */
    public function __construct(
        IPostRepository $post_repo,
        IAuthPostRepository $auth_post_repo,
        ICategoryRepository $category_repo,
        IUserRepository $user_repo
    ) {
        $this->post_repo        = $post_repo;
        $this->auth_post_repo   = $auth_post_repo;
        $this->category_repo    = $category_repo;
        $this->user_repo        = $user_repo;
        $this->pagination_count = config(
            'constants.PAGINATION_COUNT'
        );

        $this->middleware([
            'auth',
            'verified'
        ])->except([
            'index',
            'search',
            'show_by_slug',
            'show_by_category',
            'show_by_archive',
            'show_by_author',
            'show_by_tag'
        ]);
    }

    /**
     * Show all posts in frontend index view.
     *
     * @return Illuminate\Support\Facades\View (frontend.index, compact('posts'))
     */
    public function index()
    {
        $posts = $this->post_repo->all_posts_paginate(
            $this->pagination_count
        );
        return view('frontend.index', compact('posts'));
    }

    /**
     * Search all posts & show them in frontend index view.
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Support\Facades\View (frontend.index, compact('posts'))
     */
    public function search(Request $request)
    {
        try {
            $posts = $this->post_repo->posts_search(
                $request,
                $this->pagination_count
            );
            return view('frontend.index', compact('posts'));
        } catch (\Throwable $th) {
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * show post by slug in frontend post view.
     *
     * @param string $slug
     * @return Illuminate\Support\Facades\View (frontend.post, compact('posts'))
     */
    public function show_by_slug($slug)
    {
        try {
            $post = $this->post_repo->post_get_by_slug_with_relations($slug);
            return (!$post)? redirect_to(
                'frontend.index'
            ): view('frontend.post', compact('post'));
        } catch (\Throwable $th) {
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * show posts by tag in frontend index view.
     *
     * @param string|int $key
     * @return Illuminate\Support\Facades\View (frontend.index, compact('posts'))
     */
    public function show_by_tag($key)
    {
        $tag = Tag::whereSlug($key)->orWhere('id', $key)->first()->id;

        if ($tag) {
            $posts = Post::with(['media', 'user', 'tags'])
                ->whereHas('tags', function ($query) use ($key) {
                    $query->where('slug', $key);
                })
                ->typePost()
                ->active()
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('frontend.index', compact('posts'));
        }

        return redirect()->route('frontend.index');
    }

    /**
     * show posts by category in frontend index view.
     *
     * @param string|int $key
     * @return Illuminate\Support\Facades\View (frontend.index, compact('posts'))
     */
    public function show_by_category($key)
    {
        try{
            $category_id = $this->category_repo->category_get_by_key($key)->id;
            if (!$category_id) return redirect_to('frontend.index');

            $posts = $this->post_repo->posts_get_by_category_with_relations(
                $category_id,
                $this->pagination_count
            );
            return view('frontend.index', compact('posts'));
        } catch (\Throwable $th) {
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * show posts by archive in frontend index view.
     *
     * @param string $date
     * @return Illuminate\Support\Facades\View (frontend.index, compact('posts'))
     */
    public function show_by_archive($date)
    {
        try{
            $posts = $this->post_repo->posts_get_by_date_with_relations(
                $date,
                $this->pagination_count
            );
            return view('frontend.index', compact('posts'));
        } catch (\Throwable $th) {
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * show posts by username in frontend index view.
     *
     * @param string $username
     * @return Illuminate\Support\Facades\View (frontend.index, compact('posts'))
     */
    public function show_by_author($username)
    {
        try{
            $user_id = $this->user_repo->user_get_by_username($username)->id;
            if (!$user_id) return redirect_to('frontend.index');

            $posts = $this->post_repo->posts_get_by_user_with_relations(
                $user_id,
                $this->pagination_count
            );
            return view('frontend.index', compact('posts'));
        } catch (\Throwable $th) {
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * Show form for creating new post of an authenticated user.
     *
     * @return Illuminate\Support\Facades\View ('frontend.user.post.create', compact('posts'))
     */
    public function create()
    {
        try {
            $tags = Tag::pluck('name', 'id')->toArray();
            $categories = $this->category_repo->all_categories_pluck();
            return (!$categories)? redirect_with_msg(
                'frontend.profile',
                'Oops, Something went wrong',
                'danger',
                auth()->user()->username
            ): view('frontend.user.post.create', compact('categories', 'tags'));
        } catch (\Exception $e) {
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * Store post data from creating new post form.
     *
     * @return Illuminate\Http\Response redirect()
     */
    public function store(PostRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->auth_post_repo->post_store($request);
            DB::commit();

            return redirect_with_msg(
                'user.posts.create',
                'Post created successfully',
                'success'
            );
        } catch (\Exception $th) {
            DB::rollback();
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * Show form for Editing a post of an authenticated user.
     *
     * @return Illuminate\Support\Facades\View ('frontend.user.post.edit', compact('post', 'categories))
     */
    public function edit($key)
    {
        try {
            $tags = Tag::pluck('name', 'id')->toArray();
            $post = $this->auth_post_repo->post_get_by_key($key);
            if (!$post) return redirect_to('frontend.index');

            $categories = $this->category_repo->all_categories_pluck();

            return view('frontend.user.post.edit', compact('post','categories', 'tags'));
        } catch (\Exception $e) {
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * Update post data from edit post form view.
     *
     * @return Illuminate\Http\Response redirect()
     */
    public function update(PostRequest $request, $key)
    {
        try {
            $post = $this->auth_post_repo->post_get_by_key($key);
            if(!$post) return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );

            DB::beginTransaction();
            $this->auth_post_repo->post_update($request, $post);
            if(count($request->tags)){
                $tags = [];
                foreach ($request->tags as $tag) {
                    $tag = Tag::firstOrCreate(
                        ['id'   => $tag],
                        ['name' => $tag],
                    );
                    $tags[] = $tag->id;
                }
                $post->tags()->sync($tags);
            }
            DB::commit();
            Cache::forget('global_tags');

            return redirect_with_msg(
                'user.posts.edit',
                'Post updated successfully',
                'success',
                $post->slug
            );

        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }

    /**
     * Delete post data and media from db and server.
     *
     * @return Illuminate\Http\Response redirect()
     */
    public function destroy($key)
    {
        try {
            $post = $this->auth_post_repo->post_get_by_key($key);
            if(!$post) return redirect_with_msg(
                'frontend.profile',
                'Oops, Something went wrong.',
                'danger',
                auth()->user()->username
            );

            DB::beginTransaction();
            $this->auth_post_repo->post_delete_by_id($post->id);
            DB::commit();

            return redirect_with_msg(
                'frontend.profile',
                'Post deleted successfully',
                'success',
                auth()->user()->username
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect_with_msg(
                'frontend.profile',
                'Oops, Something went wrong.',
                'danger',
                auth()->user()->username
            );
        }
    }
}

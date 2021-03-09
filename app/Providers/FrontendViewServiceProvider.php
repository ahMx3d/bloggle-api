<?php

namespace App\Providers;

use App\Models\Tag;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\Frontend\Repositories\IPostRepository;
use App\Interfaces\Frontend\Repositories\ICommentRepository;
use App\Interfaces\Frontend\Repositories\ICategoryRepository;

class FrontendViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @param object $post_repo
     * @param object $comment_repo
     * @param object $category_repo
     *
     * @return void
     */
    public function boot(
        IPostRepository $post_repo,
        ICommentRepository $comment_repo,
        ICategoryRepository $category_repo
    )
    {
        if (!request()->is('admin/*')) {
            // Frontend Custom Pagination
            Paginator::defaultView('vendor.pagination.boighor');

            view()->composer('*', function ($view) use(
                $post_repo,
                $comment_repo,
                $category_repo
            )
            {
                $limitation_count = config(
                    'constants.LIMITATION_COUNT'
                );

                if(!Cache::has('recent_posts')){
                    $recent_posts = $post_repo->all_posts_limit(
                        $limitation_count
                    );
                    Cache::remember(
                        'recent_posts',
                        3600,
                        function () use($recent_posts) {
                            return $recent_posts;
                        }
                    );
                }

                if(!Cache::has('recent_comments')){
                    $recent_comments = $comment_repo->all_comments_limit(
                        $limitation_count
                    );
                    Cache::remember(
                        'recent_comments',
                        3600,
                        function () use($recent_comments) {
                            return $recent_comments;
                        }
                    );
                }

                if (!Cache::has('global_tags')) {
                    $global_tags = Tag::withCount(['posts'=> function ($query){
                        $query->typePost()->active();
                    }])->get();

                    Cache::remember('global_tags', 3600, function () use ($global_tags){
                        return $global_tags;
                    });
                }

                if(!Cache::has('sidebar_categories')){
                    $sidebar_categories = $category_repo->all_categories_get();
                    Cache::remember(
                        'sidebar_categories',
                        3600,
                        function () use($sidebar_categories) {
                            return $sidebar_categories;
                        }
                    );
                }

                if(!Cache::has('sidebar_archives')){
                    $sidebar_archives = $post_repo->archived_posts_pluck()->toArray();
                    Cache::remember(
                        'sidebar_archives',
                        3600,
                        function () use($sidebar_archives) {
                            return $sidebar_archives;
                        }
                    );
                }

                $recent_posts       = Cache::get('recent_posts');
                $recent_comments    = Cache::get('recent_comments');
                $sidebar_categories = Cache::get('sidebar_categories');
                $global_tags        = Cache::get('global_tags');
                $sidebar_archives   = Cache::get('sidebar_archives');

                $view->with([
                    'recent_posts'       => $recent_posts,
                    'recent_comments'    => $recent_comments,
                    'sidebar_categories' => $sidebar_categories,
                    'global_tags'        => $global_tags,
                    'sidebar_archives'   => $sidebar_archives,
                ]);
            });
        }
    }
}

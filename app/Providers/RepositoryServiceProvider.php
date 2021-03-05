<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Frontend\PageRepository;
use App\Repositories\Frontend\PostRepository;
use App\Repositories\Frontend\UserRepository;
use App\Repositories\Frontend\CommentRepository;
use App\Repositories\Frontend\ContactRepository;
use App\Repositories\Frontend\AuthPostRepository;
use App\Repositories\Frontend\AuthUserRepository;
use App\Repositories\Frontend\CategoryRepository;
use App\Repositories\Frontend\AuthMediaRepository;
use App\Interfaces\Frontend\Repositories\IPageRepository;
use App\Interfaces\Frontend\Repositories\IPostRepository;
use App\Interfaces\Frontend\Repositories\IUserRepository;
use App\Interfaces\Frontend\Repositories\ICommentRepository;
use App\Interfaces\Frontend\Repositories\IContactRepository;
use App\Interfaces\Frontend\Repositories\IAuthPostRepository;
use App\Interfaces\Frontend\Repositories\IAuthUserRepository;
use App\Interfaces\Frontend\Repositories\ICategoryRepository;
use App\Interfaces\Frontend\Repositories\IAuthMediaRepository;

class RepositoryServiceProvider extends ServiceProvider
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
     * @return void
     */
    public function boot()
    {
        $this->app->bind(               // Post Repository Interface
            IPostRepository::class,
            PostRepository::class
        );
        $this->app->bind(               // Post Repository Interface
            IAuthPostRepository::class,
            AuthPostRepository::class
        );
        $this->app->bind(               // Contact Repository Interface
            IContactRepository::class,
            ContactRepository::class
        );
        $this->app->bind(               // Page Repository Interface
            IPageRepository::class,
            PageRepository::class
        );
        $this->app->bind(               // Comment Repository Interface
            ICommentRepository::class,
            CommentRepository::class
        );
        $this->app->bind(               // Category Repository Interface
            ICategoryRepository::class,
            CategoryRepository::class
        );
        $this->app->bind(               // User Repository Interface
            IUserRepository::class,
            UserRepository::class
        );
        $this->app->bind(               // Media Repository Interface
            IAuthMediaRepository::class,
            AuthMediaRepository::class
        );
        $this->app->bind(               // User Repository Interface
            IAuthUserRepository::class,
            AuthUserRepository::class
        );
    }
}

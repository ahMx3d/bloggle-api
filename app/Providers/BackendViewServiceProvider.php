<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class BackendViewServiceProvider extends ServiceProvider
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
        if (request()->is('admin/*')) {
            view()->composer('*', function ($view)
            {
                // if(Cache::has('admin_sidebar_menu')) Cache::forget('admin_sidebar_menu');
                if(!Cache::has('admin_sidebar_menu')) Cache::forever(
                    'admin_sidebar_menu',
                    Permission::tree()
                );
                $admin_sidebar_menu = Cache::get('admin_sidebar_menu');
                $view->with([
                    'admin_sidebar_menu' => $admin_sidebar_menu,
                ]);
            });
        }
    }
}

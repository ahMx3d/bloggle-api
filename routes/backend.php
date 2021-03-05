<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register backend routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "backend" middleware group. Now create something great!
|
*/

Route::group([
    'middleware' => [
        'roles',
        'role:admin|editor'
    ],
    'prefix'     => 'admin',
    'namespace'  => 'Backend',
], function () {
    Route::any(
        '/notifications/get',
        'NotificationController@get'
    );
    Route::any(
        '/notifications/read',
        'NotificationController@markAsRead'
    );
    Route::any(
        '/notifications/read/{id}',
        'NotificationController@markAsReadAndRedirect'
    );

    Route::get('/index', [
        'uses' => 'DashboardsController@index'
    ]);

    Route::group(['as' => 'admin.'], function () {
        Route::get('/dashboard', [
            'as'   => 'index',
            'uses' => 'DashboardsController@index'
        ]);
        Route::post('/post/media/{id}/remove', [
            'as'   => 'post.media.destroy',
            'uses' => 'MediaController@destroy'
        ]);
        Route::post('/users/remove-image', [
            'as'   => 'users.media.destroy',
            'uses' => 'UsersController@destroy_image'
        ]);
        Route::post('/supervisors/remove-image', [
            'as'   => 'supervisors.media.destroy',
            'uses' => 'SupervisorsController@destroy_image'
        ]);

        Route::resources([
            'posts'           => 'PostsController',
            'pages'           => 'PagesController',
            'post_comments'   => 'CommentsController',
            'post_categories' => 'CategoriesController',
            'post_tags'       => 'TagsController',
            'contact_us'      => 'ContactsController',
            'users'           => 'UsersController',
            'supervisors'     => 'SupervisorsController',
            'settings'        => 'SettingsController',
        ]);
    });
});

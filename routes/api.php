<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::group([
//     'middleware' => 'auth:api',
//     'namespace'  => 'Api',
// ], function () {

// });

Route::group(['namespace' => 'Api'], function () {

    Route::any(
        'notifications/',
        'NotificationController@index'
    );
    Route::any(
        'notifications/read',
        'NotificationController@store'
    );

    Route::get(
        'users/{user:username}/posts/{post:slug}/media/{media:id}',
        'UserPostController@destroyUserPostMedia'
    );
    Route::get(
        'users/{user:username}/posts/{post:slug}',
        'UserPostController@show'
    );

    Route::post('logout', 'AuthController@logout');
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('refresh-token', 'AuthController@getRefreshToken');

    Route::patch(
        'users/{user:username}/password',
        'UserController@passwordUpdate'
    );
    Route::apiResources([
        'comments'   => 'CommentController',
        'users'      => 'UserController',
        'categories' => 'CategoryController',
        'posts'      => 'PostController',
        'tags'       => 'TagController',
    ]);
});

Route::group(['namespace' => 'Backend\Api'], function () {
    Route::get(
        'charts/comments',
        'ChartController@comments'
    );
    Route::get(
        'charts/users',
        'ChartController@users'
    );
});

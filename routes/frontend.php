<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register frontend routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "frontend" middleware group. Now create something great!
|
*/


// Landing Routes
Route::group(['namespace' => 'Frontend'], function () {
    Route::get('/', [
        'as'   => 'frontend.index',
        'uses' => 'PostsController@index'
    ]);

    Route::get('posts', [
        'as'   => 'frontend.search',
        'uses' => 'PostsController@search'
    ]);

    Route::get('pages/{slug}', [
        'as'   => 'frontend.pages.show',
        'uses' => 'PagesController@index'
    ]);

    Route::post('/contact-us', [
        'as'   => 'frontend.contacts.add',
        'uses' => 'ContactsController@store'
    ]);

    Route::get('/{slug}', [
        'as'   => 'frontend.posts.show',
        'uses' => 'PostsController@show_by_slug'
    ]);

    Route::post('posts/{slug}/comments', [
        'as'   => 'frontend.posts.comments.add',
        'uses' => 'CommentsController@store'
    ]);

    Route::get('tags/{key}', [
        'as'   => 'frontend.posts.by.tag',
        'uses' => 'PostsController@show_by_tag'
    ]);

    Route::get('categories/{key}', [
        'as'   => 'frontend.categories.posts.show',
        'uses' => 'PostsController@show_by_category'
    ]);

    Route::get('archives/{date}', [
        'as'   => 'frontend.archives.posts.show',
        'uses' => 'PostsController@show_by_archive'
    ]);

    Route::get('authors/{username}', [
        'as'   => 'frontend.authors.posts.show',
        'uses' => 'PostsController@show_by_author'
    ]);
});


Route::group([
    'middleware' => 'verified',
    'namespace'  => 'Frontend'
], function () {
    Route::get('/profiles/{username?}', [
        'as'   => 'frontend.profile',
        'uses' => 'UsersController@index'
    ]);

    Route::any(
        'user/notifications/get',
        'NotificationsController@get'
    );
    Route::any(
        'user/notifications/read',
        'NotificationsController@mark_as_read'
    );
    Route::any(
        'user/notifications/read/{id}',
        'NotificationsController@mark_as_read_and_redirect'
    );

    Route::get('/profiles/{username?}/info/password', [
        'as'   => 'frontend.profile.password.edit',
        'uses' => 'Auth\ChangePasswordController@edit'
    ]);
    Route::put('/profiles/{username?}/info/password', [
        'as'   => 'frontend.profile.password.update',
        'uses' => 'Auth\ChangePasswordController@update'
    ]);

    Route::get('/profiles/{username?}/info', [
        'as'   => 'frontend.profile.info.edit',
        'uses' => 'UsersController@edit'
    ]);
    Route::put('/profiles/{username?}/info', [
        'as'   => 'frontend.profile.info.update',
        'uses' => 'UsersController@update'
    ]);

    Route::get('/comments/show/', [
        'as'   => 'user.comments.index',
        'uses' => 'CommentsController@index'
    ]);
    Route::get('/edit-comments/{id}', [
        'as'   => 'user.comment.edit',
        'uses' => 'CommentsController@edit'
    ]);
    Route::put('/update-comments/{id}', [
        'as'   => 'user.comment.update',
        'uses' => 'CommentsController@update'
    ]);
    Route::delete('/delete-comments/{id}', [
        'as'   => 'user.comment.delete',
        'uses' => 'CommentsController@destroy'
    ]);

    Route::get('/posts/new', [
        'as'   => 'user.posts.create',
        'uses' => 'PostsController@create'
    ]);
    Route::post('/posts/new', [
        'as'   => 'user.posts.store',
        'uses' => 'PostsController@store'
    ]);

    Route::get('/posts/{post}', [
        'as'   => 'user.posts.edit',
        'uses' => 'PostsController@edit'
    ]);
    Route::put('/posts/{post}', [
        'as'   => 'user.posts.update',
        'uses' => 'PostsController@update'
    ]);

    Route::delete('users/posts/{post}', [
        'as'   => 'user.posts.delete',
        'uses' => 'PostsController@destroy'
    ]);

    Route::post('/posts/media/{id}', [
        'as'   => 'user.posts.media.destroy',
        'uses' => 'MediaController@destroy'
    ]);

    Route::patch('/profiles/{user:username}/image', [
        'as'   => 'user.image.delete',
        'uses' => 'UsersController@destroy'
    ]);
});

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here is where you can register authentication routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "authentication" middleware group. Now create something great!
|
*/

// Frontend Authentication Routes...
Route::group(['namespace' => 'Frontend\Auth'], function () {

    Route::group(['as' => 'auth.provider.', 'prefix' => 'auth/{provider}'], function () {
        Route::get('redirect', [
            'as'   => 'redirect',
            'uses' => 'LoginController@redirectToProvider'
        ]);

        Route::get('callback', [
            'as'   => 'callback',
            'uses' => 'LoginController@handleProviderCallback'
        ]);
    });

    Route::group(['as' => 'frontend.'], function () {
        Route::get('/login', [
            'as'   => 'show_login_form',
            'uses' => 'LoginController@showLoginForm'
        ]);
        Route::post('login', [
            'as'   => 'login',
            'uses' => 'LoginController@login'
        ]);

        Route::post('logout', [
            'as'   => 'logout',
            'uses' => 'LoginController@logout'
        ]);

        Route::get('register', [
            'as'   => 'show_register_form',
            'uses' => 'RegisterController@showRegistrationForm'
        ]);
        Route::post('register', [
            'as'   => 'register',
            'uses' => 'RegisterController@register'
        ]);
    });

    Route::group(['as' => 'password.', 'prefix' => 'password'], function () {
        Route::get('reset', [
            'as'   => 'request',
            'uses' => 'ForgotPasswordController@showLinkRequestForm'
        ]);
        Route::post('email', [
            'as'   => 'email',
            'uses' => 'ForgotPasswordController@sendResetLinkEmail'
        ]);
        Route::get('reset/{token}', [
            'as'   => 'reset',
            'uses' => 'ResetPasswordController@showResetForm'
        ]);
        Route::post('reset', [
            'as'   => 'update',
            'uses' => 'ResetPasswordController@reset'
        ]);
    });

    Route::group(['as' => 'verification.', 'prefix' => 'email'], function () {
        Route::get('verify', [
            'as'   => 'notice',
            'uses' => 'VerificationController@show'
        ]);
        Route::get('verify/{id}/{hash}', [
            'as'   => 'verify',
            'uses' => 'VerificationController@verify'
        ]);
        Route::post('resend', [
            'as'   => 'resend',
            'uses' => 'VerificationController@resend'
        ]);
    });
});

// Backend Authentication Routes...
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Backend\Auth'], function () {
    Route::get('/login', [
        'as'   => 'show_login_form',
        'uses' => 'LoginController@showLoginForm'
    ]);
    Route::post('login', [
        'as'   => 'login',
        'uses' => 'LoginController@login'
    ]);

    Route::post('logout', [
        'as'   => 'logout',
        'uses' => 'LoginController@logout'
    ]);

    // Route::get('register', [
    //     'as'   => 'show_register_form',
    //     'uses' => 'RegisterController@showRegistrationForm'
    // ]);
    // Route::post('register', [
    //     'as'   => 'register',
    //     'uses' => 'RegisterController@register'
    // ]);

    // Route::get('password/reset', [
    //     'as'   => 'password.request',
    //     'uses' => 'ForgotPasswordController@showLinkRequestForm'
    // ]);
    // Route::post('password/email', [
    //     'as'   => 'password.email',
    //     'uses' => 'ForgotPasswordController@sendResetLinkEmail'
    // ]);
    // Route::get('password/reset/{token}', [
    //     'as'   => 'password.reset',
    //     'uses' => 'ResetPasswordController@showResetForm'
    // ]);
    // Route::post('password/reset', [
    //     'as'   => 'password.update',
    //     'uses' => 'ResetPasswordController@reset'
    // ]);

    // Route::get('email/verify', [
    //     'as'   => 'verification.notice',
    //     'uses' => 'VerificationController@show'
    // ]);
    // Route::get('email/verify/{id}/{hash}', [
    //     'as'   => 'verification.verify',
    //     'uses' => 'VerificationController@verify'
    // ]);
    // Route::post('email/resend', [
    //     'as'   => 'verification.resend',
    //     'uses' => 'VerificationController@resend'
    // ]);
});

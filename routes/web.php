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
Route::get('/login', [
    'as'   => 'frontend.show_login_form',
    'uses' => 'Frontend\Auth\LoginController@showLoginForm'
]);
Route::post('login', [
    'as'   => 'frontend.login',
    'uses' => 'Frontend\Auth\LoginController@login'
]);

Route::post('logout', [
    'as'   => 'frontend.logout',
    'uses' => 'Frontend\Auth\LoginController@logout'
]);

Route::get('register', [
    'as'   => 'frontend.show_register_form',
    'uses' => 'Frontend\Auth\RegisterController@showRegistrationForm'
]);
Route::post('register', [
    'as'   => 'frontend.register',
    'uses' => 'Frontend\Auth\RegisterController@register'
]);

Route::get('password/reset', [
    'as'   => 'password.request',
    'uses' => 'Frontend\Auth\ForgotPasswordController@showLinkRequestForm'
]);
Route::post('password/email', [
    'as'   => 'password.email',
    'uses' => 'Frontend\Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::get('password/reset/{token}', [
    'as'   => 'password.reset',
    'uses' => 'Frontend\Auth\ResetPasswordController@showResetForm'
]);
Route::post('password/reset', [
    'as'   => 'password.update',
    'uses' => 'Frontend\Auth\ResetPasswordController@reset'
]);

Route::get('email/verify', [
    'as'   => 'verification.notice',
    'uses' => 'Frontend\Auth\VerificationController@show'
]);
Route::get('email/verify/{id}/{hash}', [
    'as'   => 'verification.verify',
    'uses' => 'Frontend\Auth\VerificationController@verify'
]);
Route::post('email/resend', [
    'as'   => 'verification.resend',
    'uses' => 'Frontend\Auth\VerificationController@resend'
]);

// Backend Authentication Routes...
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', [
        'as'   => 'admin.show_login_form',
        'uses' => 'Backend\Auth\LoginController@showLoginForm'
    ]);
    Route::post('login', [
        'as'   => 'admin.login',
        'uses' => 'Backend\Auth\LoginController@login'
    ]);

    Route::post('logout', [
        'as'   => 'admin.logout',
        'uses' => 'Backend\Auth\LoginController@logout'
    ]);
});

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
});

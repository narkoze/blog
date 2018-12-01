<?php

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

Route::post('signup', 'Auth\RegisterController@register');
Route::post('passwordresetemail', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('passwordreset', 'Auth\ResetPasswordController@reset');
Route::post('signin', 'AuthController@signin');

Route::get('post/{post}/comment', 'PostCommentController@index');

Route::apiResources([
    'post' => 'PostController',
]);

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::get('signout', 'AuthController@signout');
    Route::get('emailresend/{id}', 'Auth\VerificationController@resend');

    Route::get('profile', 'Profile\ProfileController@index');
    Route::put('profile', 'Profile\ProfileController@update');
    Route::delete('profile', 'Profile\ProfileController@destroy');
    Route::post('profile/image', 'Profile\ImageController@index');
    Route::delete('profile/image', 'Profile\ImageController@destroy');

    Route::post('post/{post}/comment', 'PostCommentController@store');
    Route::put('post/{post}/comment/{comment}', 'PostCommentController@update');
    Route::delete('post/{post}/comment/{comment}', 'PostCommentController@destroy');

    Route::group([
        'prefix' => 'admin',
        'middleware' => 'admin'
    ], function () {
        Route::get('dashboard/counts', 'Admin\DashboardController@counts');
        Route::get('dashboard/users', 'Admin\DashboardController@users');
        Route::get('dashboard/posts', 'Admin\DashboardController@posts');
        Route::get('dashboard/comments', 'Admin\DashboardController@comments');
        Route::get('dashboard/tags', 'Admin\DashboardController@tags');

        Route::apiResources([
            'post' => 'Admin\PostController',
            'role' => 'Admin\RoleController',
            'user' => 'Admin\UserController',
            'tag' => 'Admin\TagController',
            'image' => 'Admin\ImageController',
        ]);
    });
});

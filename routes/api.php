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

    Route::group([
        'prefix' => 'admin',
    ], function () {
        Route::apiResources([
            'post' => 'Admin\PostController',
            'role' => 'Admin\RoleController',
        ]);
    });
});

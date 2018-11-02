<?php

use Illuminate\Http\Request;

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

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::get('auth', 'AuthController@auth');
    Route::get('signout', 'AuthController@signout');
    Route::get('emailresend/{id}', 'Auth\VerificationController@resend');
});

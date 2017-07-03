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

Route::post('auth/login', 'Api\AuthController@authenticate');
Route::post('registerIpad', 'Api\IpadsController@register');

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::get('me', 'Api\UserController@me');
    Route::get('meetups', 'Api\MeetupsController@all');
    Route::get('meetups/{id}', 'Api\MeetupsController@show');
    Route::get('activityBucket/{id}', 'Api\ActivityBucketsController@show');

    //Results

    Route::get('meetups/freeze/{id}', 'Api\MeetupsController@freeze');
});


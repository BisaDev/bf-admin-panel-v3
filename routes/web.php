<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();
//Putting Auth routes individually to remove the Registration Routes which Auth::routes() adds by default.
Route::get('login',     ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login',    ['as' => '', 'uses' => 'Auth\LoginController@login']);
Route::post('logout',   ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

Route::post('password/email',           ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset',            ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/reset',           ['as' => '', 'uses' => 'Auth\ResetPasswordController@reset']);
Route::get('password/reset/{token}',    ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);

Route::group(['middleware' => ['auth']], function(){
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::resource('grade_levels',                     'GradeLevelController');
    Route::post('grade_levels/{grade_level}',           'GradeLevelController@show')->name('grade_levels.show.search');

    Route::resource('subjects',                         'SubjectController', ['except' => ['index', 'create']]); 
    Route::get('subjects/create/{grade_level_id}',      'SubjectController@create')->name('subjects.create');
    Route::post('subjects/{subject}',                   'SubjectController@show')->name('subjects.show.search');

    Route::resource('topics',                           'TopicController', ['except' => ['index', 'show', 'create']]);
    Route::get('topics/create/{subject_id}',            'TopicController@create')->name('topics.create');

    Route::resource('locations',                        'LocationController');
    Route::post('locations/search',                     'LocationController@index')->name('locations.search');
    Route::post('locations/{location}',                 'LocationController@show')->name('locations.show.search');
    Route::post('locations/toggle_active/{location}',   'LocationController@toggle_active')->name('locations.toggle_active');

    Route::resource('rooms',                            'RoomController', ['except' => ['index', 'create']]);
    Route::get('rooms/create/{location_id}',            'RoomController@create')->name('rooms.create');
});

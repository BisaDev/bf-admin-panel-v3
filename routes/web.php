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
    Route::post('subjects/get_by_grade',                'SubjectController@get_subjects_by_grade_level')->name('subjects.by_grade');
    Route::post('subjects/{subject}',                   'SubjectController@show')->name('subjects.show.search');

    Route::resource('topics',                           'TopicController', ['except' => ['index', 'show', 'create']]);
    Route::get('topics/create/{subject_id}',            'TopicController@create')->name('topics.create');
    Route::post('topics/get_by_subject',                'TopicController@get_topics_by_subject')->name('topics.by_subject');

    Route::match(['get', 'post'], 'locations/search',   'LocationController@index')->name('locations.search');
    Route::resource('locations',                        'LocationController');
    Route::post('locations/{location}',                 'LocationController@show')->name('locations.show.search');
    Route::post('locations/toggle_active/{location}',   'LocationController@toggle_active')->name('locations.toggle_active');

    Route::resource('rooms',                            'RoomController', ['except' => ['index', 'create']]);
    Route::get('rooms/create/{location_id}',            'RoomController@create')->name('rooms.create');
    Route::post('rooms/get_by_location',                'RoomController@get_rooms_by_location')->name('rooms.by_location');

    Route::match(['get', 'post'], 'employees/search',   'UserController@index')->name('employees.search');
    Route::resource('employees',                        'UserController');
    Route::post('employees/get_by_location',            'UserController@get_employees_by_location')->name('employees.by_location');

    Route::match(['get', 'post'], 'students/search',    'StudentController@index')->name('students.search');
    Route::resource('students',                         'StudentController');
    Route::post('students/{student}',                   'StudentController@show')->name('students.show.search');

    Route::resource('family_members',                           'FamilyMemberController', ['except' => ['index', 'create']]);
    Route::get('family_members/create/{subject_id}',            'FamilyMemberController@create')->name('family_members.create');
    Route::post('family_members/toggle_pickup/{family_member}', 'FamilyMemberController@toggle_pickup')->name('family_members.toggle_pickup');
    Route::post('family_members/toggle_active/{family_member}', 'FamilyMemberController@toggle_active')->name('family_members.toggle_active');

    Route::resource('minigames',    'MinigameController');

    Route::match(['get', 'post'], 'questions/search',   'QuestionController@index')->name('questions.search');
    Route::resource('questions',                        'QuestionController');
    Route::post('questions/get_for_quiz',               'QuestionController@get_questions_for_quiz')->name('questions.for_quiz');

    Route::match(['get', 'post'], 'quizzes/search', 'QuizController@index')->name('quizzes.search');
    Route::resource('quizzes',                      'QuizController');
    Route::get('quizzes/{quiz}/reorder',            'QuizController@reorder_questions')->name('quizzes.reorder_questions');
    Route::post('quizzes/get_for_activity_bucket',  'QuizController@get_quizzes_for_activity_bucket')->name('quizzes.for_activity_bucket');
    Route::post('quizzes/save_question_order',      'QuizController@save_question_order')->name('quizzes.save_question_order');

    Route::match(['get', 'post'], 'activity_buckets/search',    'ActivityBucketController@index')->name('activity_buckets.search');
    Route::resource('activity_buckets',                         'ActivityBucketController');
    Route::get('activity_buckets/create/{meetup_id?}',          'ActivityBucketController@create')->name('activity_buckets.create');
    Route::get('activity_buckets/{activity_bucket}/reorder',    'ActivityBucketController@reorder_quizzes')->name('activity_buckets.reorder_quizzes');
    Route::post('activity_buckets/get_for_meetup',              'ActivityBucketController@get_activity_buckets_for_meetup')->name('activity_buckets.for_meetup');
    Route::post('activity_buckets/save_quiz_order',             'ActivityBucketController@save_quiz_order')->name('activity_buckets.save_quiz_order');

    Route::resource('meetups',                  'MeetupController');
    Route::get('meetups/attendance/{meetup}',   'MeetupController@attendance')->name('meetups.attendance');
    Route::post('meetups/attendance/{meetup}',  'MeetupController@attendance_store')->name('meetups.attendance.store');
    Route::post('meetups/filter',               'MeetupController@index')->name('meetups.filter');

    Route::post('tags/repository',  'TagController@repository')->name('tags.repository');
});

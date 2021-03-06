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

Route::domain(config('app.student_url'))->group(function () {

    Route::group(['middleware' => ['auth']], function(){

        Route::match(['get', 'post'], '/',              'StudentDashboardController@index')->name('student_dashboard');
//        Route::get('/', 'StudentDashboardController@index')->name('student_dashboard');
        Route::post('answer_sheet/',                    'AnswerSheetController@create_exam')->name('answer_sheet.create_exam');
        Route::get('answer_sheet/{examSectionID}',      'AnswerSheetController@show_answer_sheet')->name('answer_sheet.show_answer_sheet');
        Route::post('answer_sheet/{examSectionID}',     'AnswerSheetController@save_answers')->name('answer_sheet.save_answers');
        Route::match(['get', 'post'], 'results/{exam}', 'AnswerSheetController@show_results')->name('answer_sheet.show_results');
        Route::post('results/{exam}',                   'AnswerSheetController@edit_understood')->name('answer_sheet.edit_understood');
        Route::match(['get', 'post'], 'analytics/',     'AnswerSheetController@analytics')->name('answer_sheet.analytics');
    });
});

Route::group(['middleware' => ['auth', 'role:admin|director|instructor']], function(){

    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::get('taggingtool',                           'TaggingToolController@index')->name('taggingtool');
    Route::get('taggingtool/tag/{subject_id?}',         'TaggingToolController@tag')->name('taggingtool.tag');
    Route::post('taggingtool/set_topic',                'TaggingToolController@set_topic')->name('taggingtool.set_topic');

    Route::get('question/list/{subject_id?}',           'TaggingQuestionController@questions_list')->name('taggingquestion.list');

    Route::resource('image-upload',                     'ImageUploadController', ['except' =>['show']]);
    Route::post('image-upload/upload',                  'ImageUploadController@upload')->name('imageupload.upload');

    Route::get('image-download/question/{topic_id?}',   'ImageDownloadController@question')->name('imagedownload.question');
    Route::get('image-download/download',               'ImageDownloadController@download')->name('imagedownload.download');

    Route::resource('taggingsubjects',                  'TaggingSubjectController');
    Route::get('TaggingSubjectController/getsubjects',  'TaggingSubjectController@getSubjects')->name('taggingsubjects.subjects');

    Route::resource('taggingtopics',                    'TaggingTopicController',['except' =>['index', 'show']]);
    Route::get('taggingtopics/create/{subject_id}',     'TaggingTopicController@create')->name('taggingtopics.create');

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
    Route::post('locations/{location}/toggle_active',   'LocationController@toggle_active')->name('locations.toggle_active');

    Route::resource('rooms',                            'RoomController', ['except' => ['index', 'create']]);
    Route::get('rooms/create/{location_id}',            'RoomController@create')->name('rooms.create');
    Route::post('rooms/get_by_location',                'RoomController@get_rooms_by_location')->name('rooms.by_location');

    Route::match(['get', 'post'], 'employees/search',   'UserController@index')->name('employees.search');
    Route::resource('employees',                        'UserController');
    Route::post('employees/get_by_location',            'UserController@get_employees_by_location')->name('employees.by_location');

    Route::match(['get', 'post'], 'students/search',    'StudentController@index')->name('students.search');
    Route::resource('students',                         'StudentController');
    Route::post('students/{student}/save_notes',        'StudentController@save_notes')->name('students.save_notes');
    Route::post('students/{student}',                   'StudentController@show')->name('students.show.search');
    Route::get('students/{student}/progress',           'StudentController@student_progress_print')->name('students.progress');

    Route::resource('family_members',                           'FamilyMemberController', ['except' => ['index', 'create']]);
    Route::get('family_members/create/{subject_id}',            'FamilyMemberController@create')->name('family_members.create');
    Route::post('family_members/{family_member}/save_notes',    'FamilyMemberController@save_notes')->name('family_members.save_notes');
    Route::post('family_members/{family_member}/toggle_pickup', 'FamilyMemberController@toggle_pickup')->name('family_members.toggle_pickup');
    Route::post('family_members/{family_member}/toggle_active', 'FamilyMemberController@toggle_active')->name('family_members.toggle_active');

    Route::resource('minigames',    'MinigameController');

    Route::match(['get', 'post'], 'questions/search',   'QuestionController@index')->name('questions.search');
    Route::get('questions/csv_question_importer',       'QuestionController@csv_question_importer')->name('questions.csv_importer');
    Route::post('questions/store_csv',                  'QuestionController@store_csv')->name('questions.store_csv');
    Route::resource('questions',                        'QuestionController');
    Route::post('questions/get_for_quiz',               'QuestionController@get_questions_for_quiz')->name('questions.for_quiz');

    Route::match(['get', 'post'], 'quizzes/search', 'QuizController@index')->name('quizzes.search');
    Route::resource('quizzes',                      'QuizController');
    Route::get('quizzes/{quiz}/reorder',            'QuizController@reorder_questions')->name('quizzes.reorder_questions');
    Route::post('quizzes/get_for_activity_bucket',  'QuizController@get_quizzes_for_activity_bucket')->name('quizzes.for_activity_bucket');
    Route::post('quizzes/save_question_order',      'QuizController@save_question_order')->name('quizzes.save_question_order');
    Route::get('quizzes/{quiz}/print',              'QuizController@show_print')->name('quizzes.show_print');

    Route::match(['get', 'post'], 'activity_buckets/search',    'ActivityBucketController@index')->name('activity_buckets.search');
    Route::resource('activity_buckets',                         'ActivityBucketController');
    Route::get('activity_buckets/create/{meetup_id?}',          'ActivityBucketController@create')->name('activity_buckets.create');
    Route::get('activity_buckets/{activity_bucket}/reorder',    'ActivityBucketController@reorder_quizzes')->name('activity_buckets.reorder_quizzes');
    Route::post('activity_buckets/get_for_meetup',              'ActivityBucketController@get_activity_buckets_for_meetup')->name('activity_buckets.for_meetup');
    Route::post('activity_buckets/save_quiz_order',             'ActivityBucketController@save_quiz_order')->name('activity_buckets.save_quiz_order');

    Route::resource('meetups',                                          'MeetupController');
    Route::get('meetups/{meetup}/attendance',                           'MeetupController@attendance')->name('meetups.attendance');
    Route::post('meetups/{meetup}/attendance',                          'MeetupController@attendance_store')->name('meetups.attendance.store');
    Route::post('meetups/filter',                                       'MeetupController@index')->name('meetups.filter');
    Route::match(['get', 'post'], 'meetups/{meetup}/student/{student}', 'MeetupController@student_detail')->name('meetups.student_detail');
    Route::get('meetups/{meetup}/student/{student}/print',              'MeetupController@student_detail_print')->name('meetups.student_detail_print');

    Route::post('tags/repository',  'TagController@repository')->name('tags.repository');

    Route::get('exams/logs',                                'ExamPrepController@logs')->name('exams.logs');
    Route::match(['get', 'post'], 'exams/logs/results',     'ExamPrepController@generate_report')->name('exams.generate_report');
    Route::post('exams/logs/get_for_results',               'ExamPrepController@get_sections_for_results')->name('exams.sections_for_results');
    Route::post('exams/logs/get_sections_for_exam',         'ExamPrepController@get_sections_for_exam')->name('exams.sections_for_exam');
    Route::resource('exams',                                'ExamPrepController', ['except' => ['edit', 'update']]);
    Route::get('exams/{exam}/{exam_section}',               'ExamPrepController@exam_section_show')->name('exams.section.show');
    Route::get('exams/{exam}/{exam_section}/edit',          'ExamPrepController@exam_section_edit')->name('exams.section.edit');
    Route::post('exams/{exam}/{exam_section}/update',       'ExamPrepController@exam_section_update')->name('exams.section.update');
});

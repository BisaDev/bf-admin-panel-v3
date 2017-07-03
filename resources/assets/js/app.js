
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import index from './pages/index';
import index_academic_content from './pages/index-academic-content';
import create from './pages/create';
import create_question from './pages/create-question';
import create_quiz from './pages/create-quiz';
import create_activity_bucket from './pages/create-activity-bucket';
import create_meetup from './pages/create-meetup';
import show_student from './pages/show-student';

const vue_elements = [
    index,
    index_academic_content,
    create,
    create_question,
    create_quiz,
    create_activity_bucket,
    create_meetup,
    show_student
];

vue_elements.forEach(function(element){
    element.init();
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



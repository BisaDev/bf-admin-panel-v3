/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Lightbox from 'vue-pure-lightbox';
import VoerroTagsInput from '@voerro/vue-tagsinput';

Vue.use(Lightbox);

Vue.component('chronometer', require('./components/Chronometer'));
Vue.component('student-answer-sheet', require('./components/studentAnswerSheet'));
Vue.component('student-answer-sheet-3', require('./components/studentAnswerSheetMath'));
Vue.component('show-results-table', require('./components/showResultsTable'));
Vue.component('tags-input', VoerroTagsInput);
Vue.component('upload-file', require('./components/uploadFile'));
Vue.component('take-practice-exam', require('./components/takePracticeExamModal'));
Vue.component('act-answer-sheet', require('./components/ACTAnswerSheet'));
Vue.component('tab', require('./components/tab'));
Vue.component('tabs', require('./components/tabs'));
Vue.component('upload-image', require('./components/uploadImage'));
Vue.component('tagging-tool', require('./components/tagging-tool/taggingTool'));
Vue.component('up-wrapper', require('./components/tagging-tool/upWrapper'));
Vue.component('up-input-group', require('./components/tagging-tool/upInputGroup'));
Vue.component('up-inputs', require('./components/tagging-tool/upInputs'));

const app = new Vue({
    el: '#app'
});

import index from './pages/index';
import index_academic_content from './pages/index-academic-content';
import index_users from './pages/index-users';
import create from './pages/create';
import create_student from './pages/create_student';
import create_question from './pages/create-question';
import create_quiz from './pages/create-quiz';
import create_activity_bucket from './pages/create-activity-bucket';
import create_meetup from './pages/create-meetup';
import show_student from './pages/show-student';
import take_practice_exam from './pages/take_practice_exam';
import print from './pages/print';
import upload_file from './pages/upload-file';
import generate_results from './pages/generate_results';
import student_dashboard from './pages/student-dashboard';

const vue_elements = [
    index,
    index_academic_content,
    index_users,
    create,
    create_student,
    create_question,
    create_quiz,
    create_activity_bucket,
    create_meetup,
    show_student,
    take_practice_exam,
    print,
    upload_file,
    generate_results,
    student_dashboard,
];

vue_elements.forEach(function (element) {
    element.init();
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
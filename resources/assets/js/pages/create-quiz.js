import datepicker from 'bootstrap-datepicker';
import getAcademicContent from './mixins/getAcademicContent';
import imagePreview from './mixins/imagePreview';
import tagRepository from './mixins/tagRepository';
import draggable from 'vuedraggable'

export default {
    init () {
        const domElement = 'create-quiz'
        if(document.getElementById(domElement)) {
            
            require('typeahead.js');
            require('bootstrap-tagsinput');
            
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#create-quiz',
            data: {
                quiz_id: 0,
                questions: [],
                type: '',
                subject: '',
                created_at: '',
                questions_selected: [],
                questions_url: '',
                tags_selected: [],
            },
            components:{
                draggable,
            },
            mixins: [getAcademicContent, imagePreview, tagRepository],
            beforeMount: function () {

                //Look for question type and assign the selected to Vue data value
                if($('#type').length > 0){
                    this.type = $('#type').children('option:selected').val();
                }

                if(this.$el.attributes['data-quiz-id'] !== undefined) {
                    this.quiz_id = this.$el.attributes['data-quiz-id'].value;
                }
            },
            watch: {
                created_at: function (val) {
                    this.loadQuestions();
                },
                tags_selected: function(val) {
                    this.loadQuestions();
                },
            },
            methods: {
                loadQuestions(){

                    if(this.type != '' && this.subject != ''){

                        this.questions = [];
                        let questions = this.questions;

                        axios.post(this.questions_url, {
                            type: this.type,
                            subject: this.subject,
                            tags: this.tags_selected,
                            created_at: this.created_at,
                        })
                        .then(function (response) {

                            $.each(response.data, function(i, item){
                                questions.push(item);
                            });
                        });
                    }
                },
                onDragEnd(url, event){
                    axios.post(url, {
                        questions: this.questions_selected,
                        quiz_id: this.quiz_id
                    })
                    .then(function (response) {

                    });
                },
                questionSelected: function(question){
                    return _.findIndex(this.questions_selected, function(d) { return d.id == question.id;}) >= 0;
                },
                selectQuestion: function(question){
                    let index = _.findIndex(this.questions_selected, function(d) { return d.id == question.id;});
                    if(index === -1){
                        this.questions_selected.push({title: question.title, photo: question.photo, id: question.id});
                    }else{
                        this.questions_selected.splice(index, 1);
                    }
                },
                clearFilter: function(){
                    this.created_at = '';
                },
            },
            computed: {
                dragOptions () {
                    return  {
                        handle: '.drag_handle'
                    };
                },
            },
            mounted() {
                if(this.$el.attributes['data-questions'] !== undefined) {
                    let questions = $.parseJSON(this.$el.attributes['data-questions'].value);
                    let vue_instance = this;
                    
                    $.each(questions, function(index, question){
                        vue_instance.questions_selected.push({title: question.title, photo: question.photo, id: question.id});
                    });
                }

                if(this.$el.attributes['data-questions-url'] !== undefined) {
                    this.questions_url = this.$el.attributes['data-questions-url'].value;
                }

                let datepicker_input = $('.datepicker-general');

                datepicker_input.datepicker({
                    autoclose: true,
                }).on(
                    "changeDate", () => { this.created_at = datepicker_input.val() }
                );
            }
        });
    },
}

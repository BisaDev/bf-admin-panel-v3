import getAcademicContent from './mixins/getAcademicContent';
import imagePreview from './mixins/imagePreview';
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
                questions_selected: []
            },
            components:{
                draggable
            },
            mixins: [getAcademicContent, imagePreview],
            beforeMount: function () {

                //Look for question type and assign the selected to Vue data value
                if($('#type').length > 0){
                    this.type = $('#type').children('option:selected').val();
                }

                if(this.$el.attributes['data-quiz-id'] !== undefined) {
                    this.quiz_id = this.$el.attributes['data-quiz-id'].value;
                }
            },
            methods: {
                loadQuestions(url, event){
                    if(this.type != '' && this.subject != ''){
                        
                        this.questions = [];
                        let questions = this.questions;

                        axios.post(url, {
                            type: this.type,
                            subject: this.subject
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
                }
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
                    
                    $.each(questions, function(index, questions){
                        vue_instance.questions_selected.push({title: questions.title, photo: questions.photo, id: questions.id});
                    });
                }

                let url = $('#tags').data('tag_repository');

                $('#tags').tagsinput({
                    tagClass: 'label label-primary',
                    typeaheadjs: 
                    [{
                        //options
                    },
                    {
                        async: true,
                        source: function (query, processSync, processAsync) {
                            return axios.post(url, {query: query}).then(function(response){ return processAsync(response.data); });
                        }
                    }]
                });
            }
        });
    },
}

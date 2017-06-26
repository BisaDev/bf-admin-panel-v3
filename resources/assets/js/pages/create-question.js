import getAcademicContent from './mixins/getAcademicContent';
import imagePreview from './mixins/imagePreview';
import tagRepository from './mixins/tagRepository';

export default {
    init () {
        const domElement = 'create-question'
        if(document.getElementById(domElement)) {

            require('typeahead.js');
            require('bootstrap-tagsinput');

            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#create-question',
            data: {
                children: [],
                type: '',
                photo: '',
            },
            mixins: [getAcademicContent, imagePreview, tagRepository],
            beforeMount: function () {

                //Look for question type and assign the selected to Vue data value
                if($('#type').length > 0){
                    this.type = $('#type').children('option:selected').val();
                }
            },
            methods: {
                addChildren(){
                    if(this.children.length < 4){
                        this.children.push({name: '', photo: '', is_correct: false, remove_photo: false});
                    }
                },
                removeChildren(index){
                    this.children.splice(index, 1);
                },
                saveQuestionAndAddMore(event){
                    $(event.target).siblings('[name="add_more"]').val('true');
                    this.$el.children[0].submit();
                }
            },
            mounted() {

                if(this.$el.attributes['data-answers'] !== undefined) {
                    let answers = $.parseJSON(this.$el.attributes['data-answers'].value);
                    let vue_instance = this;
                    
                    $.each(answers, function(index, answer){
                        vue_instance.children.push({name: answer.text, photo: answer.photo, is_correct: (answer.is_correct == 1 || answer.is_correct == 'on')? true : false, remove_photo: false, id: answer.id});
                    });
                }
            }
        });
    },
}

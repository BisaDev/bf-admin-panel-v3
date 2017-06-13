import getAcademicContent from './mixins/getAcademicContent';
import imagePreview from './mixins/imagePreview';

export default {
    init () {
        const domElement = 'create-quiz'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#create-quiz',
            data: {
                children: [],
                type: '',
                photo: '',
            },
            mixins: [getAcademicContent, imagePreview],
            beforeMount: function () {

                //Look for question type and assign the selected to Vue data value
                if($('#type').length > 0){
                    this.type = $('#type').children('option:selected').val();
                }
            },
            methods: {
                addChildren(){
                    this.children.push({name: '', photo: '', is_correct: false});
                },
                removeChildren(index){
                    this.children.splice(index, 1);
                }
            },
            mounted() {
            }
        });
    },
}

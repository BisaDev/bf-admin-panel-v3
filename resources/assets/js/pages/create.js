import datepicker from 'bootstrap-datepicker';
import imagePreview from './mixins/imagePreview';

export default {
    init () {
        const domElement = 'create-container'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#create-container',
            data: {
                children: [],
                type: '',
                photo: '',
            },
            mixins: [imagePreview],
            beforeMount: function () {

                //Look for question type and assign the selected to Vue data value
                if($('#type').length > 0){
                    this.type = $('#type').children('option:selected').val();
                }
            },
            methods: {
                addChildren(){
                    this.children.push({name: ''});
                },
                removeChildren(index){
                    this.children.splice(index, 1);
                },
            },
            mounted() {

                $('.datepicker-birthday').datepicker({
                    autoclose: true,
                    startView: 'decade'
                });
            }
        });
    },
}

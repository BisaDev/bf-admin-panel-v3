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
                photo: ''
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

                if(this.$el.attributes['data-notes'] !== undefined) {
                    let notes = $.parseJSON(this.$el.attributes['data-notes'].value);
                    let vue_instance = this;
                    
                    $.each(notes, function(index, notes){
                        vue_instance.children.push({name: notes.title, text: notes.text, id: notes.id});
                    });
                }

                $('.datepicker-birthday').datepicker({
                    autoclose: true,
                    startView: 'decade'
                });
            }
        });
    },
}

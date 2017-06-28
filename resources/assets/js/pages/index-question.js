import indexGeneral from './mixins/indexGeneral';
import getAcademicContent from './mixins/getAcademicContent';

export default {
    init () {
        const domElement = 'index-question'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#index-question',
            data: {
                type: ''
            },
            mixins: [indexGeneral, getAcademicContent],
            beforeMount: function () {

                //Look for question type and assign the selected to Vue data value
                if($('#type').length > 0){
                    this.type = $('#type').children('option:selected').val();
                }
            },
            methods: {
                removeSearch(){
                    $('#search').val('');
                    $('#filter-form').submit();
                },
            }
        });
    },
}

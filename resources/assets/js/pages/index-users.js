import indexGeneral from './mixins/indexGeneral';

export default {
    init () {
        const domElement = 'index-users'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#index-users',
            data: {
                location: '',
                role: ''
            },
            mixins: [indexGeneral],
            beforeMount: function () {

                this.location = $('#location').children('option:selected').val();
                this.role = $('#role').children('option:selected').val();
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

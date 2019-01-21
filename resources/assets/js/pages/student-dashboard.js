export default {
    init () {
        const domElement = 'student-dashboard'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#student-dashboard',
            data: {
                showAllExams: false,
            },
            methods: {},
            mounted() {
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });

                if ($('input[name="showAllExams"]').data('old') === 'on') {
                    this.showAllExams = true;
                }
            }
        });
    },
}

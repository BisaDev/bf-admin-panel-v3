import imagePreview from "./mixins/imagePreview";
import managesChildren from "./mixins/managesChildren";

export default {
    init () {
        const domElement = 'create-student'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#create-student',
            data: {
                photo: '',
                children: [],
                studentUserShow: false,
            },
            mixins: [imagePreview, managesChildren],
            methods: {

            },
            mounted() {
                $('.datepicker-birthday').datepicker({
                    autoclose: true,
                    startView: 'decade'
                });

                if ($('input[name="add_user"]').data('old') === 'on') {
                    this.studentUserShow = true;
                }
            }
        });
    },
}

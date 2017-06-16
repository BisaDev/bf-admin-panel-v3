import getAcademicContent from './mixins/getAcademicContent';
import draggable from 'vuedraggable'

export default {
    init () {
        const domElement = 'create-activity-bucket'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#create-activity-bucket',
            data: {
                activity_bucket_id: 0,
                quizzes: [],
                subject: '',
                quizzes_selected: []
            },
            components:{
                draggable
            },
            mixins: [getAcademicContent],
            beforeMount: function () {

                if(this.$el.attributes['data-activity-bucket-id'] !== undefined) {
                    this.activity_bucket_id = this.$el.attributes['data-activity-bucket-id'].value;
                }
            },
            methods: {
                loadQuizzes(url, event){
                    if(this.subject != ''){
                        
                        this.quizzes = [];
                        let quizzes = this.quizzes;

                        axios.post(url, {
                            type: this.type,
                            subject: this.subject
                        })
                        .then(function (response) {

                            $.each(response.data, function(i, item){
                                quizzes.push(item);
                            });
                        });
                    }
                },
                onDragEnd(url, event){
                    axios.post(url, {
                        quizzes: this.quizzes_selected,
                        activity_bucket_id: this.activity_bucket_id
                    })
                    .then(function (response) {

                    });
                },
                quizSelected: function(quiz){
                    return _.findIndex(this.quizzes_selected, function(d) { return d.id == quiz.id;}) >= 0;
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
                if(this.$el.attributes['data-quizzes'] !== undefined) {
                    let quizzes = $.parseJSON(this.$el.attributes['data-quizzes'].value);
                    let vue_instance = this;
                    
                    $.each(quizzes, function(index, quizzes){
                        vue_instance.quizzes_selected.push({title: quizzes.title, description: quizzes.description, id: quizzes.id});
                    });
                }
            }
        });
    },
}

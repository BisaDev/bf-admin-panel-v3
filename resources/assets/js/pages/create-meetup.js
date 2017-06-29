import getAcademicContent from './mixins/getAcademicContent';
import managesChildren from './mixins/managesChildren';
import draggable from 'vuedraggable'

export default {
    init () {
        const domElement = 'create-meetup'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#create-meetup',
            data: {
                activity_buckets: [],
                students: [],
                students_selected: [],
                subject: '',
                add_existing_activity_bucket: false,
                selected_activity_bucket: 0
            },
            components:{
                draggable
            },
            mixins: [getAcademicContent, managesChildren],
            beforeMount: function () {
                if($('#existing_ab').length > 0 && $('#existing_ab').data('checked') != ''){
                    this.add_existing_activity_bucket = true;
                    this.selected_activity_bucket = $('#existing_ab').data('checked');
                }
            },
            methods: {
                loadActivityBuckets(url, event){
                    if(this.add_existing_activity_bucket && this.subject != ''){
                        
                        this.activity_buckets = [];
                        let activity_buckets = this.activity_buckets;

                        axios.post(url, {
                            subject: this.subject
                        })
                        .then(function (response) {

                            $.each(response.data, function(i, item){
                                activity_buckets.push(item);
                            });
                        });
                    }
                },
                getRoomsAndInstructorsFromLocation(rooms_url, instructors_url, event){

                    axios.post(rooms_url, {
                        location_id: event.target.value,
                    })
                    .then(function (response) {
                        var option = new Option('Select room', '');
                        $("#room").html('').append(option);

                        $.each(response.data, function(i, item){
                            var option = new Option(item.name, item.id);
                            $("#room").append(option);
                        });

                        if($('#room').data('selected') != ''){
                        
                            $('#room option[value='+$('#room').data('selected')+']').prop('selected',true);
                            const event = document.createEvent('HTMLEvents');
                            event.initEvent('change', true, true);
                            
                            $('#room')[0].dispatchEvent(event);
                        }
                    });

                    axios.post(instructors_url, {
                        location_id: event.target.value,
                    })
                    .then(function (response) {
                        var option = new Option('Select instructor', '');
                        $("#user").html('').append(option);

                        $.each(response.data, function(i, item){
                            var option = new Option(item.full_name, item.id);
                            $("#user").append(option);
                        });

                        if($('#user').data('selected') != ''){
                        
                            $('#user option[value='+$('#user').data('selected')+']').prop('selected',true);
                            const event = document.createEvent('HTMLEvents');
                            event.initEvent('change', true, true);
                            
                            $('#user')[0].dispatchEvent(event);
                        }
                    });
                },
                activityBucketSelected: function(activity_bucket){
                    return this.selected_activity_bucket == activity_bucket.id;
                },
                studentSelected: function(student){
                    return _.findIndex(this.students_selected, function(d) { return d.id == student.id;}) >= 0;
                }
            },
            mounted() {
                require('bootstrap-timepicker');

                $('.datepicker-meetup').datepicker({
                    autoclose: true,
                    startDate: new Date()
                });

                $('.timepicker').timepicker();

                if($('#location').length > 0 && $('#location').data('selected') != ''){
                    $('#location option[value='+$('#location').data('selected')+']').prop('selected',true);
                    const event = document.createEvent('HTMLEvents');
                    event.initEvent('change', true, true);
                    
                    $('#location')[0].dispatchEvent(event);
                }

                if(this.$el.attributes['data-students'] !== undefined) {
                    let students = $.parseJSON(this.$el.attributes['data-students'].value);
                    let vue_instance = this;
                    
                    $.each(students, function(index, student){
                        vue_instance.students.push({name: student.full_name, photo: student.photo, id: student.id});
                    });
                }

                if(this.$el.attributes['data-students-selected'] !== undefined) {
                    let students_selected = $.parseJSON(this.$el.attributes['data-students-selected'].value);
                    let vue_instance = this;
                    
                    $.each(students_selected, function(index, student){
                        vue_instance.students_selected.push({name: student.full_name, photo: student.photo, id: student.id});
                    });
                }
            }
        });
    },
}

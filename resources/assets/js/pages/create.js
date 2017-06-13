import datepicker from 'bootstrap-datepicker';

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
                },
                getSubjectsFromGradeLevel(url, event){

                    axios.post(url, {
                        grade_level_id: event.target.value,
                    })
                    .then(function (response) {
                        var option = new Option('Select subject', '');
                        $("#subject").html('').append(option);

                        $.each(response.data, function(i, item){
                            var option = new Option(item.name, item.id);
                            $("#subject").append(option);
                        });

                        if($('#subject').data('selected') != ''){
                        
                            $('#subject option[value='+$('#subject').data('selected')+']').prop('selected',true);
                            const event = document.createEvent('HTMLEvents');
                            event.initEvent('change', true, true);
                            
                            $('#subject')[0].dispatchEvent(event);
                        }
                    });
                },
                getTopicsFromSubject(url, event){

                    axios.post(url, {
                        subject_id: event.target.value,
                    })
                    .then(function (response) {
                        var option = new Option('Select topic', '');
                        $("#topic").html('').append(option);
                        
                        $.each(response.data, function(i, item){
                            var option = new Option(item.name, item.id);
                            $("#topic").append(option);
                        });

                        if($('#topic').data('selected') != ''){
                            $('#topic option[value='+$('#topic').data('selected')+']').prop('selected',true);
                        }
                    });
                },
                onFileChange(e, index = null){
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createImage(files[0], index);
                },
                createImage(file, index){
                    var reader = new FileReader();
                    var vm = this;

                    reader.onload = (e) => {
                        if(index === null){
                            vm.photo = e.target.result;
                        }else{
                            vm.children[index].photo = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                },
                saveQuestionAndAddMore(event){
                    $(event.target).siblings('[name="add_more"]').val('true');
                    this.$el.children[0].submit();
                }
            },
            mounted() {

                if($('#grade_level').length > 0 && $('#grade_level').data('selected') != ''){
                    
                    $('#grade_level option[value='+$('#grade_level').data('selected')+']').prop('selected',true);
                    const event = document.createEvent('HTMLEvents');
                    event.initEvent('change', true, true);
                    
                    $('#grade_level')[0].dispatchEvent(event);
                }

                if(this.$el.attributes['data-answers'] !== undefined) {
                    let answers = $.parseJSON(this.$el.attributes['data-answers'].value);
                    let vue_instance = this;
                    
                    $.each(answers, function(index, answer){
                        vue_instance.children.push({name: answer.text, photo: answer.photo, is_correct: (answer.is_correct == 1)? true : false, id: answer.id});
                    });
                }

                $('.datepicker').datepicker({
                    autoclose: true,
                    startView: 'decade'
                });
            }
        });
    },
}

var getAcademicContent = {
    methods: {
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
    },
    mounted() {

        if($('#grade_level').length > 0 && $('#grade_level').data('selected') != ''){
            
            $('#grade_level option[value='+$('#grade_level').data('selected')+']').prop('selected',true);
            const event = document.createEvent('HTMLEvents');
            event.initEvent('change', true, true);
            
            $('#grade_level')[0].dispatchEvent(event);
        }
    }
}
export default getAcademicContent;
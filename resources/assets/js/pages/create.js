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

            },
            methods: {
                addChildren(){
                    this.children.push({name: '', photo: ''});
                    
                    let this_instance = this;

                    Vue.nextTick(function () {
                        this_instance.initFilestyle();
                    })
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
                    });
                },
                initFilestyle(){
                    $(".filestyle").filestyle({size: "sm", buttonText: ""});
                },
                onFileChange(e, index = null) {
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createImage(files[0], index);
                },
                createImage(file, index) {
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
                }
            },
            mounted() {
                this.initFilestyle();

                $('.datepicker').datepicker({
                    autoclose: true,
                    startView: 'decade'
                });
            }
        });
    },
}

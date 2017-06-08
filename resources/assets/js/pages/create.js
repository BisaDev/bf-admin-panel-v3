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
                children: []
            },
            beforeMount: function () {

            },
            methods: {
                addChildren(){
                    this.children.push({name: ''});
                },
                removeChildren(index){
                    this.children.splice(index, 1);
                }
            },
            mounted() {
                $(".filestyle").filestyle({size: "sm"});

                $('.datepicker').datepicker({
                    autoclose: true,
                    startView: 'decade'
                });
            }
        });
    },
}

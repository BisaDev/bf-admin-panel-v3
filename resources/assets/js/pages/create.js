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
                
            },
            beforeMount: function () {

            },
            methods: {

            },
            mounted() {
                $(".filestyle").filestyle({size: "sm"});
            }
        });
    },
}

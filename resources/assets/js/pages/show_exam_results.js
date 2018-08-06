export default {
    init () {
        const domElement = 'show-results'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#show-results',
            data: {
                understoodQuestion: ''
            },
            methods: {
                understood(url, event){
                    // this.understoodQuestion = [];
                    // let understoodQuestion = this.understoodQuestion;

                    // axios.post(url, {

                    // });

                }
            },
        });
    },
}

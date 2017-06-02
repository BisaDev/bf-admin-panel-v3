export default {
    init () {
        const domElement = 'add-topics'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#add-topics',
            data: {
                topics: []
            },
            beforeMount: function () {

            },
            methods: {
                addTopic(){
                    this.topics.push({name: ''});
                },
                removeTopic(index){
                    this.topics.splice(index, 1);
                }
            },
            mounted() {
                
            }
        });
    },
}

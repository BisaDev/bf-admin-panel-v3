export default {
    init () {
        const domElement = 'add-subjects'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#add-subjects',
            data: {
                subjects: []
            },
            beforeMount: function () {

            },
            methods: {
                addSubject(){
                    this.subjects.push({name: ''});
                },
                removeSubject(index){
                    this.subjects.splice(index, 1);
                }
            },
            mounted() {
                
            }
        });
    },
}

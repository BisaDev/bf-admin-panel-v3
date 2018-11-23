
export default {
    init () {
        const domElement = 'take-practice-exam'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#take-practice-exam',
        });
    },
}

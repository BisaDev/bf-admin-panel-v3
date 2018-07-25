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
                selected: []
            },
            // methods: {
            //     changeState: function () {
            //         this.selected.push
            //     }
            // },e
        });
    },
}

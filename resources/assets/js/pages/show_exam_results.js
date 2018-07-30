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
            methods: {
                toggleState: function () {
                    this.selected = !this.selected;
                }
            },
        });
    },
}

export default {
    init () {
        const domElement = 'tooltip-math'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#tooltip-math',
            data: {},
            mounted() {
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })
            }
        });
    },
}

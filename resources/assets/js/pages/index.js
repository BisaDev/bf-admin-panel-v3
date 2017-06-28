import indexGeneral from './mixins/indexGeneral';

export default {
    init () {
        const domElement = 'index-container'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#index-container',
            mixins: [indexGeneral],
        });
    },
}

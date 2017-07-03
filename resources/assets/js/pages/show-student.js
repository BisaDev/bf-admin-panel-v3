import indexGeneral from './mixins/indexGeneral';
import managesChildren from './mixins/managesChildren';

export default {
    init () {
        const domElement = 'show-student'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#show-student',
            mixins: [indexGeneral, managesChildren],
        });
    },
}

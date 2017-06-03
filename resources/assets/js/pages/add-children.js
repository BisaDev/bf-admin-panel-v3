export default {
    init () {
        const domElement = 'add-children'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#add-children',
            data: {
                children: []
            },
            methods: {
                addChildren(){
                    this.children.push({name: ''});
                },
                removeChildren(index){
                    this.children.splice(index, 1);
                }
            }
        });
    },
}

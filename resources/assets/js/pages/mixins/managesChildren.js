var managesChildren = {
    data: {
        children: []
    },
    methods: {
        addChildren(){
            this.children.push({name: ''});
        },
        removeChildren(index){
            this.children.splice(index, 1);
        },
    },
    mounted() {
        if(this.$el.attributes['data-notes'] !== undefined) {
            let notes = $.parseJSON(this.$el.attributes['data-notes'].value);
            let vue_instance = this;

            $.each(notes, function(index, notes){
                vue_instance.children.push({name: notes.title, text: notes.text, id: notes.id});
            });
        }
    }
}
export default managesChildren;
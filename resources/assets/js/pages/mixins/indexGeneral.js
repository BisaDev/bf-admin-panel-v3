import swal from 'sweetalert2';

var indexGeneral = {
    data: {
        dbModel: null,
        dbModelChild: null,
        search: null,
    },
    beforeMount: function () {
        
        this.dbModel = this.$el.attributes['data-model'].value;
        
        if(this.$el.attributes['data-model-child'] !== undefined) {
            this.dbModelChild = this.$el.attributes['data-model-child'].value;
        }
        
        if(this.$el.attributes['data-search'] !== undefined) {
            this.search = this.$el.attributes['data-search'].value;
        }
    },
    methods: {
        removeSearch(url){
            window.location.href = url;
        },
        confirmDelete(item_id, child_elements, event){
            event.preventDefault();
            
            let vue_instance = this;
            let confirmation_text = "This action can't be undone. ";
            if(child_elements > 0 && this.dbModelChild != ''){
                confirmation_text += 'Deleting this '+this.dbModel+' will also delete '+child_elements+' '+this.dbModelChild+(child_elements > 1? 's' : '');
            }

            swal({
                title: 'Are you sure?',
                text: confirmation_text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#23527c',
                cancelButtonColor: '#f05050',
                confirmButtonText: 'Delete'
            }).then(function () {
                
                vue_instance.deleteItem(item_id);
            }, function (dismiss) {
                
                if (dismiss === 'cancel') {
                    
                }
            })
        },
        deleteItem(item_id, event){
            
            $('#delete-form-'+item_id).submit();
        },
        toggleActive(url, event){

            axios.post(url, {
                status: event.target.checked,
            })
            .then(function (response) {
                console.log(response);
            });
        }
    },
    mounted() {
        
    }
}
export default indexGeneral;
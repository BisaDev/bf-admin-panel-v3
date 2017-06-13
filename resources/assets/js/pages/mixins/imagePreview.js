var imagePreview = {
    methods: {
        onFileChange(e, index = null){
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0], index);
        },
        createImage(file, index){
            var reader = new FileReader();
            var vm = this;

            reader.onload = (e) => {
                if(index === null){
                    vm.photo = e.target.result;
                }else{
                    vm.children[index].photo = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        },
    },
}
export default imagePreview;
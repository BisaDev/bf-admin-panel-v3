import swal from 'sweetalert2';

let imagePreview = {
    methods: {
        /*onFileChange(e, index = null){
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0], index);
        },*/
        createImage(e, index = null){

            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;

            let reader = new FileReader();
            let vm = this;

            reader.onload = (e2 => {
                let image_src = e2.target.result;

                vm.validateImage(image_src, index, function(invalid){

                    if(invalid){
                        e.target.value = null;
                        let title, text;

                        if(index !== null){
                            title = 'Invalid Image';
                            text = 'Answer images must be square.';
                        }else{
                            switch (vm.type) {
                                case '4': //Drag and drop
                                    title = 'Invalid Image';
                                    text = 'Image must be 1366 x 512.';
                                    break;
                                case '3': //Apple pencil
                                case '5': //Touch select
                                case '6': //Research and Report back
                                    title = 'Invalid Image';
                                    text = 'Image must be 1024 x 512.';1366
                                    break;
                                case '':
                                    title = 'Select Question Type';
                                    text = 'Please select a question type before adding an image.'
                            }
                        }

                        swal({
                            title: title,
                            text: text,
                            type: 'warning',
                            confirmButtonColor: '#23527c',
                            cancelButtonColor: '#f05050',
                            confirmButtonText: 'Dismiss'
                        })
                    }else{
                        if(index === null){
                            vm.photo = image_src;
                        }else{
                            vm.children[index].photo = image_src;
                        }
                    }
                });
            });

            reader.readAsDataURL(files[0]);
        },
        validateImage(image_src, index, callback){

            let image = new Image();
            let vue_instance = this;
            let invalid_image = false;

            image.onload = function () {

                if(index !== null){
                    if (image.width !== image.height) {
                        invalid_image = true;
                    }
                }else{
                    switch (vue_instance.type) {
                        case '4': //Drag and drop
                            if (image.width !== 1366 || image.height !== 512) {
                                invalid_image = true;
                            }
                            break;
                        case '3': //Apple pencil
                        case '5': //Touch select
                        case '6': //Research and Report back
                            if (image.width !== 1024 || image.height !== 512) {
                                invalid_image = true;
                            }
                            break;
                        case '':
                            invalid_image = true;
                    }
                }

                callback(invalid_image);
            };

            image.src = image_src;
        }
    },
};
export default imagePreview;
import swal from 'sweetalert2';
import cropImage from '../../components/cropImage';

export default {
    components: {
        cropImage,
    },

    methods: {
        /**
         * Open the UI to crop the image attached to the given fileInput
         *
         * @param {Event} e
         * @param {Function} handleCrop
         * @param {String} imageType
         * @param {Number|null} index
         * @returns {*|void}
         */
        openCropImage(e, handleCrop, imageType, index = null) {

            const file_input = e.target;

            if (imageType === '') {
                file_input.value = null;
                return this.$_cropImages_alertSelectQuestionType();
            }

            const files = e.target.files || e.dataTransfer.files;
            if (files.length === 0) {
                return;
            }

            const fileReader = new FileReader();
            fileReader.onload = (loadEvent) => {
                /*
                 * Create a child node to mount the cropImage component.
                 * The child node is appended to the #content-page-content
                 * element in order to make it "float" as a modal box.
                 */
                const contentPageContent = document.getElementById('content-page-content');
                const cropToolModal = document.createElement('div');
                cropToolModal.id = 'crop-tool-modal';
                contentPageContent.appendChild(cropToolModal);

                /*
                 * Create a constructor for the cropImage component, this is
                 * needed to mount the component programatically, also this
                 * allow us to hook into the "destroyed" event to properly
                 * remove the DOM element when the component is closed,
                 * see cropImage.methods.close()
                 */
                const cropToolConstructor = Vue.extend(Object.assign({}, cropImage, {
                    destroyed() {
                        file_input.value = null;
                        contentPageContent.removeChild(this.$el);
                    }
                }));

                const $this = this;

                $this.$_cropImages_validateImageRequirements(loadEvent.target.result, imageType, function(invalid){

                    if(invalid){
                        file_input.value = null;
                        let title, text;

                        switch (imageType) {
                            case '4': //Drag and drop
                                title = 'Invalid Image';
                                text = 'Image must be at least 1366 x 512.';
                                break;
                            case '3': //Apple pencil
                            case '5': //Touch select
                            case '6': //Research and Report back
                                title = 'Invalid Image';
                                text = 'Image must be at least 1024 x 512.';
                                break;
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

                        /*
                         * Instantiate the Vue component and mount it over the element
                         * created before, then just call the "open" method of the
                         * component to start using it.
                         */
                        const cropTool = new cropToolConstructor();
                        cropTool.$mount(cropToolModal);
                        cropTool.open(
                            $this.$_cropImages_buildCropOptions(loadEvent.target.result, handleCrop, imageType, index)
                        );
                    }
                });
            };

            fileReader.readAsDataURL(files[0]);
        },

        /*
         * Private methods definition.
         * See: https://vuejs.org/v2/style-guide/#Private-property-names-essential
         */

        /**
         * Alert the user to select a question type before continue.
         *
         * @returns {*|void}
         */
        $_cropImages_alertSelectQuestionType() {
            swal({
                title: 'Select Question Type',
                text: 'Please select a question type before adding an image.',
                type: 'warning',
                confirmButtonColor: '#23527c',
                cancelButtonColor: '#f05050',
                confirmButtonText: 'Dismiss',
            });
        },


        $_cropImages_validateImageRequirements(image_src, type, callback) {
            const image = new Image();

            image.onload = function () {

                let invalid_image = false;

                switch (type) {
                    case '4': //Drag and drop
                        if (image.width < 1366 || image.height < 512) {
                            invalid_image = true;
                        }
                        break;
                    case '3': //Apple pencil
                    case '5': //Touch select
                    case '6': //Research and Report back
                        if (image.width < 1024 || image.height < 512) {
                            invalid_image = true;
                        }
                        break;
                    case '':
                        invalid_image = true;
                }

                callback(invalid_image);
            };

            image.src = image_src
        },

        /**
         * Built the options object to pass to the crop image component.
         *
         * @param {File} imageFile
         * @param {String} imageType
         * @param {Number|null} index
         * @returns {Object}
         */
        $_cropImages_buildCropOptions(imageDataURL, handleCrop, imageType, index) {
            const options = {imageDataURL, handleCrop, fixedCropBox: true, size: null, index: index};

            switch (imageType) {
                case '4': // Drag & Drop
                    options.size = {width: 1366, height: 512};
                    break;
                case '3': // Apple Pencil
                case '5': // Touch Select
                case '6': // Research and Report Back
                    options.size = {width: 1024, height: 512};
                    break;
                default:
                    // Images with a given index should be square, I assume
                    // these images are for the answers of a multiple choice
                    // question. If no index and no image type is provided then
                    // the image can be any size.
                    options.fixedCropBox = false;
                    options.size = (index !== null && index !== 5) ? 'square' : 'any';
                    break;
            }

            return options;
        },
    }
};
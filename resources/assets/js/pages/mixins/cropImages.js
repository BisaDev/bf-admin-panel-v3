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

            let file_input = $(e.target);

            if (imageType === '') {
                file_input.val('');
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
                        file_input.val('');
                        contentPageContent.removeChild(this.$el);
                    }
                }));

                /*
                 * Instantiate the Vue component and mount it over the element
                 * created before, then just call the "open" method of the
                 * component to start using it.
                 */
                const cropTool = new cropToolConstructor();
                cropTool.$mount(cropToolModal);
                cropTool.open(
                    this.$_cropImages_buildCropOptions(loadEvent.target.result, handleCrop, imageType, index)
                );
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

        /**
         * Built the options object to pass to the crop image component.
         *
         * @param {File} imageFile
         * @param {String} imageType
         * @param {Number|null} index
         * @returns {Object}
         */
        $_cropImages_buildCropOptions(imageDataURL, handleCrop, imageType, index) {
            const options = {imageDataURL, handleCrop, fixedCropBox: true, size: null};

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
                    options.size = index !== null ? 'square' : 'any';
                    break;
            }

            return options;
        },
    }
};
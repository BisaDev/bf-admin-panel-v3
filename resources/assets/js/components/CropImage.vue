<template>
    <div class="crop-wrapper card-box" v-if="isVisible">
        <div class="crop-container">
            <img class="crop-image" ref="image" :src="options.imageDataURL" alt="Image">
        </div>

        <button class="btn btn-md btn-info" @click.prevent="close">Close</button>
        <button class="btn btn-md btn-primary" :disabled="cropToolReady === false" @click.prevent="crop">Crop Image</button>
    </div>
</template>

<script>
    import Cropper from 'cropperjs';

    export default {
        data() {
            return {
                options: {},
                isVisible: false,
                cropTool: null,
                cropToolReady: false,
            };
        },

        methods: {
            /**
             * Open the modal and initialize the crop tool.
             *
             * @param {Object} options
             */
            open(options) {
                this.options = options;
                this.isVisible = true;
                this.$nextTick(this.initCropTool);
            },

            /**
             * Destroy the crop tool, this component.
             */
            close() {
                this.destroyCropTool();
                this.isVisible = false;
                this.$destroy();
            },

            /**
             * Initialize the crop tool with the given options.
             */
            initCropTool() {
                const vm = this;

                this.cropTool = new Cropper(this.$refs.image, {
                    viewMode: 2,
                    zoomable: false,
                    dragMode: 'none',
                    // The crop box will be resizable only if it is not fixed.
                    //cropBoxResizable: !this.options.fixedCropBox,
                    // The aspect ratio es free only if the size isn't a square.
                    aspectRatio: vm.options.size === 'square' ? 1 : (vm.options.size.width/vm.options.size.height),

                    ready() {
                        vm.cropToolReady = true;

                        // This set the size of the crop box, the size is based
                        // on the natural size of the original image.
                        if (vm.options.fixedCropBox) {
                            this.cropper.setData({
                                width: vm.options.size.width,
                                height: vm.options.size.height,
                            });
                        }
                    },
                });

                $(this.$refs.image).on('cropmove', function (e) {
                    // Call getData() or getImageData() or getCanvasData() or
                    // whatever fits your needs
                    let data = vm.cropTool.getData();

                    // Modify the dimensions to quit from disabled mode
                    if (data.height < vm.options.size.height || data.width < vm.options.size.width) {
                        data.width = vm.options.size.width;
                        data.height = vm.options.size.height;

                        vm.cropTool.setData(data);
                    }

                    //console.log("data = %o", data);

                    // Analyze the result
                    if (data.height < vm.options.size.height || data.width < vm.options.size.width) {
                        //console.log("Minimum size reached!");

                        // Stop resize
                        return false;
                    }

                    // Continue resize
                    return true;
                });
            },

            /**
             * Destroy the crop tool.
             */
            destroyCropTool() {
                this.cropTool.destroy();
            },

            /**
             * Perform the crop action and send the cropped image data to the
             * handler.
             */
            crop() {

                this.options.handleCrop(
                    this.cropTool.getCroppedCanvas().toDataURL('image/jpeg'),
                    this.options.index
                );

                this.close();
            },
        }
    }
</script>

<style src="cropperjs/dist/cropper.css"></style>
<style scoped>
    .crop-wrapper {
        position: fixed;
        padding: 15px;
        top: 15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        z-index: 1000;
    }

    .crop-container {
        margin-bottom: 1em;
        height: 500px;
    }

    .crop-image {
        /* This rule is required, see: https://github.com/fengyuanchen/cropperjs#usage */
        max-width: 100%;
    }
</style>

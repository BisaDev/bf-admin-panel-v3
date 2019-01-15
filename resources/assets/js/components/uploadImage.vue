<template>
    <div class="create-container">
        <label class="control-label">Answer Explanation Image</label>
        <div class="col-md-12 m-b-10" v-if="image.explanation_image">
            <slot v-show="!delete_photo" />
            <button v-show="!delete_photo" type="button" class="btn btn-mid btn-danger center-block m-t-5" @click="confirmDelete">Delete Image</button>
            <input type="hidden" name="delete_photo" :value="delete_photo">
        </div>
        <div class="droppable droppable-small">
            <span v-if="!uploadedPhoto">Drag an image or click to browse</span>
            <img v-else :src="uploadedPhoto"/>
            <input name="uploadedPhoto" type="file" @change="uploadImage($event)">
            <input type="hidden" :name="`uploadedPhoto_${image.question_number}`" :value="uploadedPhoto">
        </div>
    </div>
</template>

<script>
    import imagePreview from './../pages/mixins/imagePreview';

    export default {
        props: ['image', 'event'],
        mixins: [imagePreview],
        data() {
            return {
                uploadedPhoto: '',
                delete_photo: false,
            }
        },
        methods: {
            confirmDelete(){
                let confirmation_text = "This action can't be undone. ";
                swal({
                    title: 'Are you sure?',
                    text: confirmation_text,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#23527c',
                    cancelButtonColor: '#f05050',
                    confirmButtonText: 'Delete'
                }).then(function () {
                    this.delete_photo = true;
                }, function (dismiss) {if (dismiss === 'cancel') {}});
            },
            uploadImage(e) {
                const input = e.target;
                let fileReader = new FileReader();
                fileReader.onload = (loadEvent) => {
                    this.uploadedPhoto = (loadEvent.target.result);
                };
                fileReader.readAsDataURL(input.files[0]);
            },
        },
    }
</script>

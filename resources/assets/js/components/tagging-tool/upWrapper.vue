<template>
    <div>
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-sm btn-default" @click="addInput">
                Add upload
                <span class="m-l-5">
                    <i class="fa fa-plus"></i>
                </span>
            </button>
        </div>

        <div class="form-group col-md-12" v-for="index in questions" :key="index">
            <up-input-group
                    :subjects="subjects"
                    :inputID="index"
                    :handleClick="removeInput"
                    :modalCall="updateModalImg"
            >
            </up-input-group>
        </div>
        <!-- Image Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img class="modal-img" :src="modalImageUrl" alt="modal-img" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                questions: [1],
                subjects: null,
                modalImageUrl: ""
            }
        },
        methods: {
            addInput: function () {
                const {questions} = this.$data;
                const randomId = Math.random().toString(36).substr(2, 9);
                questions.push(randomId);

            },
            removeInput: function (index) {
                const {questions} = this.$data;
                this.$data.questions = questions.filter(id => id !== index);
            },
            updateModalImg: function (imgURL) {
                const that= this;
                this.modalImageUrl = imgURL
            }
        },
        mounted: function () {
            const vueInstance = this;
            const url = vueInstance.subject_url;
            axios.get(url)
                .then(function (response) {
                    vueInstance.subjects = response.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                    console.log("dude");
                })
        },
        props: ['subject_url']
    }
</script>

<style scoped>
    .modal-img {
        max-height: 50vh;
    }

    .modal-dialog {
        min-width: fit-content;
    }

    .modal-header {
        border: none;
    }

</style>
<template>
    <div :class="`card-box wrapper  ${success ? 'alert-success' : ''}`">
        <div class="input-group col-md-12 text-left text-left" v-if="!success">
            <div class="flex">
                <a @click="addInputs" class="pointer">
                    Add image
                    <span href="#" class="m-l-5">
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
            </div>
        </div>
        <div v-for="(input ,index) in imgInputs" :key="index">
            <up-inputs
                    :uploaded="success"
                    :onModalCall="modalCall"
                    :answer.sync="imgInputs[index].answer"
                    :questionImg.sync="imgInputs[index].questionImg"
                    :explanationImg.sync="imgInputs[index].explanationImg"
                    :error="input.error"
            >
            </up-inputs>
        </div>
        <div class="form-group text-right m-t-20" v-if="!success">
            <span class="upload-msg" v-if="uploadState === 'uploading'" >
                <span class="upload-icon"></span>
            </span>
            <span class="fail-msg" v-if="uploadState === 'failed'" >Failed. Please try again</span>
            <button @click="removeItem" class="btn btn-md btn-info" :disabled="disabledButton">
                Cancel
            </button>
            <button @click="handleUpload" type="submit"
                    class="btn btn-md btn-primary" :disabled="disabledButton">
                Upload
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                imgInputs: [],
                success: false,
                uploadState: "",
                disabledButton: false
            }
        },
        methods: {
            addInputs: function () {
                this.imgInputs.push({
                    answer: "",
                    questionImg: null,
                    explanationImg: null,
                    error: {
                        answer: true,
                        questionImg: true,
                    }
                });
            },
            handleUpload: function () {
                if(this.subject) {
                    this.disabledButton = true;
                    this.uploadState = 'uploading';
                    this.$emit('update:subjectError', false);
                    this.validateInputs();

                    if(this.formIsValid()) {
                        const formData = new FormData;
                        const vueInstance = this;
                        this.uploadState = 'uploading';

                        formData.append("subject", this.subject.name);
                        formData.append("subjectID", this.subject.id);
                        this.imgInputs.forEach((inputs, index) => {
                            formData.append(`questionImg_${index}`, inputs.questionImg);
                            formData.append(`answer_${index}`, inputs.answer);
                            formData.append(`explanationImg_${index}`, inputs.explanationImg);
                        });

                        const config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        };

                        axios.post(this.postUrl, formData, config)
                            .then(function (response) {
                                if(response.data === 'Success') {
                                    vueInstance.success = true;
                                    vueInstance.uploadState = '';
                                } else {
                                    vueInstance.uploadState = 'failed';
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                                vueInstance.disabledButton = false;
                                vueInstance.uploadState = 'failed';
                            })
                    }
                } else {
                    this.$emit('update:subjectError', true)
                }
            },
            validateInputs: function () {
                const updatedSet =this.imgInputs;

                this.imgInputs.forEach( (inputGroup, index) => {
                    inputGroup['answer'] ?
                        updatedSet[index].error.answer = true :
                        updatedSet[index].error.answer = false;
                    inputGroup['questionImg'] ?
                        updatedSet[index].error.questionImg = true :
                        updatedSet[index].error.questionImg = false;

                });

                this.imgInputs = updatedSet;
            },
            formIsValid: function () {
                let inputsFilled = true;

                this.imgInputs.forEach(inputGroup => {
                    _.forEach(inputGroup.error, input => {
                        if (!input) {
                            inputsFilled = false;
                        }
                    })
                });
                return inputsFilled
            },
        },
        props: ['removeItem', 'modalCall', 'postUrl', 'subject'],
        mounted() {
            this.addInputs();
        }
    }
</script>

<style scoped>
    .wrapper {
        display: flex;
        flex-direction: column;
    }

    .wrapper.alert-success {
        padding: 20px;
    }

    .input-group {
        margin: 30px 0 10px;
    }

    .flex {
        display: flex;
        justify-content: flex-end;
    }

    .pointer {
        cursor: pointer;
    }

    .error-msg {
        color: red;
        border-color: red;
    }

</style>

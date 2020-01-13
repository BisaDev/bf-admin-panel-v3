<template>
    <div class="card-box wrapper">
        <div class="input-group col-md-12 text-left text-left">
            <div class="flex">
                <a @click="addInputs" class="pointer">
                    Add image
                    <span href="#" class="m-l-5">
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
            </div>
        </div>
        <div v-for="(input ,index,) in imgInputs" :key="index">
            <up-inputs
                    :onModalCall="modalCall"
                    v-bind.sync="imgInputs[index]"
                    :error="input.error"
            >
            </up-inputs>
        </div>
        <div class="form-group col-md-12 text-right m-t-30">
            <button @click="removeItem" class="btn btn-md btn-info">Cancel</button>
            <button @click="handleUpload" type="submit" class="btn btn-md btn-primary">Upload</button>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                imgInputs: [],
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
                        explanationImg: true,
                    }
                });
            },
            handleUpload: function () {
                this.validateInputs();

                if(this.formIsValid()) {
                    const formData = new FormData;

                    formData.append("subject", this.subject);
                    this.imgInputs.forEach((inputs, index) => {
                        formData.append(`questionImage_${index}`, inputs.questionImg);
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
                            console.log(response)
                        })
                        .catch(function (error) {
                            console.log(error)
                        })
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
                    inputGroup['explanationImg'] ?
                        updatedSet[index].error.explanationImg = true :
                        updatedSet[index].error.explanationImg = false

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

</style>

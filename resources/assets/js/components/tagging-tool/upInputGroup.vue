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
        <div v-for="(input ,index) in imgInputs" :key="index">
            <up-inputs
                    :onModalCall="modalCall"
                    v-bind.sync="imgInputs[index]"
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
                });
            },
            handleUpload: function () {
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
        props: ['removeItem',  'modalCall', 'postUrl', 'subject'],
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

<template>
    <div class="card-box wrapper">
        <div class="input-group col-md-12 text-left text-left">
            <div class="flex">
                <label class="flex column">Subject:
                    <select v-model="currentSubject" name="subject" class="form-control">
                        <option value="">Select subject</option>
                        <option v-for="subject in subjects">
                            {{subject.name}}
                        </option>
                    </select>
                </label>
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
                currentSubject: ""
            }
        },
        methods: {
            addInputs: function () {
                this.imgInputs.push({
                    questionImg: null,
                    answer: "",
                    explanationImg: null,
                });
            },
            handleUpload: function () {
                const formData = new FormData;

                formData.append("subject", this.currentSubject);
                this.imgInputs.forEach((inputs, index) => {
                    formData.append(`questionImage_${index}`, inputs.questionImg);
                    formData.append(`answer_${index}`, inputs.answer);
                    formData.append(`explanationImg_${index}`, inputs.explanationImg);
                })

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
        props: ['removeItem', 'subjects', 'modalCall', 'postUrl'],
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

    .add-img-btn {
        margin-left: 50px;
    }

    .input-group {
        margin: 30px 0 10px;
    }

    .flex {
        display: flex;
        justify-content: space-between;
    }

    .column {
        flex-direction: column;
    }

    .form-control {
        min-width: 160px;
    }

    .pointer {
        cursor: pointer;
    }

</style>

<template>
    <div class="card-box wrapper">
        <div class="input-group col-md-12 text-left text-left">
            <div class="flex">
                <label class="flex column">Subject:
                    <select v-model="currentSubject" name="subject" @change="updateSubject($event)">
                        <option v-for="subject in subjects">
                            {{subject.name}}
                        </option>
                    </select>
                </label>
                <button type="button" @click="addInputs" class="btn btn-sm btn-default add-img-btn">
                    Add image
                    <span class="m-l-5">
                        <i class="fa fa-plus"></i>
                    </span>
                </button>
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
            <button @click="handleClick(inputID)" class="btn btn-md btn-info">Cancel</button>
            <button @click="handleUpload" type="submit" class="btn btn-md btn-primary">Upload</button>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                imgInputs: [],
                currentSubject: null
            }
        },
        methods: {
            addInputs: function () {
                const {imgInputs} = this.$data;
                const ttDataSet = {
                    questionImg: null,
                    answer: "",
                    explanationImg: null,
                };
                imgInputs.push(ttDataSet);
            },
            handleUpload: function () {
                const formData = new FormData();

                const payload = [
                    {Subject: this.currentSubject},
                    formData
                ];

                const header = {
                    'Content-Type': 'multipart/form-data'
                };

                axios.post(this.postUrl, payload , header)
                    .then(function (response) {
                        console.log(response)
                    })
                    .catch(function (error) {
                        console.log(error)
                    })
            },
            updateSubject: function (event) {
                this.currentSubject = event.target.value;
            }
        },
        props: ['inputID', 'handleClick', 'subjects', 'modalCall', 'postUrl'],
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

</style>
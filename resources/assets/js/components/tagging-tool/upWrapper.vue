<template>
    <div>
        <div class="form-group col-md-12 upper-group">
            <button type="button" class="btn btn-default" @click="addInput">
                Add upload
                <span class="m-l-5">
                    <i class="fa fa-plus"></i>
                </span>
            </button>
            <div class="subject-container">
                <select v-model="currentSubject" name="subject" id="subject" class="form-control">
                    <option value="">Select subject</option>
                    <option v-for="(subject, index) in subjects" :value="index">
                        {{subject.name}}
                    </option>
                </select>
                <span :class="subjectError ? 'error-msg' : ''" v-show="subjectError">
                    Please choose a Subject
                </span>
            </div>
        </div>

        <div class="form-group col-md-12" v-for="(item, index) in questions" :key="index">
            <up-input-group
                    v-bind:subjectError.sync="subjectError"
                    :subject="subjects[currentSubject]"
                    :removeItem="() => removeInput(index)"
                    :modalCall="updateModalImg"
                    :postUrl="update_url"
            >
            </up-input-group>
        </div>
        <preview-modal
                :modalImageUrl="modalImageUrl"
        />
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                subjects: '',
                modalImageUrl: "",
                currentSubject: "",
                questions: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,19,20],
                subjectError: false
            }
        },
        methods: {
            addInput: function () {
                const {questions} = this.$data;
                const randomId = Math.random().toString(36).substr(2, 9);
                questions.push(randomId);
            },
            removeInput: function (index) {
                this.questions.splice(index, 1);
            },
            updateModalImg: function (imgURL) {
                this.modalImageUrl = imgURL
            },
        },
        mounted: function () {
            const vueInstance = this;
            const url = vueInstance.subject_url;

            axios.get(url)
                .then(function (response) {
                    vueInstance.subjects = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
        },
        props: ['subject_url' , 'update_url']
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

    .upper-group .subject-container {
        margin-left: 18px;
    }

    .upper-group {
        display: flex;
        align-items: flex-start;
    }

    .subject-input {
        min-width: 230px;
        margin-left: 18px;
    }
    .error-msg {
        color: red;
        font-size: 14px;
        border-color: red;
    }
</style>

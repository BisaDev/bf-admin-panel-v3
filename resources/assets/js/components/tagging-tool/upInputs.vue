<template>
    <div class="input-group col-md-12 text-right flex">
        <div class="flex-column">
            <label class="control-label" for="question-img">Question image:</label>
            <button type="button" data-toggle="modal" data-target="#previewModal"
                    v-on:click="onModalCall(leftImageUrl)">
                <img :src="leftImageUrl" alt="left-img"  v-if="leftImageUrl !== ''"/>
            </button>
            <input type="file" name="question-img"
                   accept="image/*" @change="previewImgUrl($event, 'left')">
        </div>
        <div class="form-group col-md-12 text-left">
            <label class="control-label" for="answer">Answer:</label>
            <input type="text" name="answer" v-model="inputsState.answerValue" class="form-control">
        </div>
        <div class="flex-column">
            <label class="control-label" for="answer-img">Answer image:</label>
            <button type="button" data-toggle="modal" data-target="#previewModal"
                    v-on:click="onModalCall(rightImageUrl)">
                <img :src="rightImageUrl" alt="right-img"  v-if="rightImageUrl !== ''"/>
            </button>
            <input type="file" @change="previewImgUrl($event, 'right')" name="answer-img" accept="image/*">
        </div>


    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                leftImageUrl: "",
                rightImageUrl: "",
                inputsState: {
                    answerValue: "",
                    questionImg: null,
                    explanationImg: null
                }
            }
        },
        methods: {
            previewImgUrl: function (event, target) {
                const vueInstance = this;
                const input = event.target;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    const formData = new FormData();

                    reader.onload = function (e) {
                        if (target === "left") {
                            vueInstance.leftImageUrl = e.target.result;
                            formData.append('questionImg' , input.files[0]);
                            vueInstance.inputsState.questionImg = formData.get('questionImg')
                        } else if (target === "right") {
                            vueInstance.rightImageUrl = e.target.result;
                            formData.append('explanationImg' , input.files[0]);
                            vueInstance.inputsState.explanationImg = formData.get('explanationImg')
                        }
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        },
        props: ['onModalCall' , 'inputValues'],
        computed: {
            textInput() {
                return this.inputsState.answerValue;
            },
            questionInput() {
                return this.inputsState.questionImg;
            },
            explanationInput() {
                return this.inputsState.explanationImg;
            },

        },
        watch: {
            textInput() {
                console.log("text input");
                this.$emit( 'update:answer' , this.inputsState.answerValue)
            },
            questionInput() {
                console.log("left upload!");
                this.$emit( 'update:questionImg' , this.inputsState.questionImg)
            },
            explanationInput() {
                this.$emit( 'update:explanationImg' , this.inputsState.explanationImg)

            },
        }
    }
</script>

<style scoped>
    .flex-column {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    button {
        border: none;
    }

    img {
        max-width: 100%;
    }

</style>
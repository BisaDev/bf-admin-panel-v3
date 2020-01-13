<template>
    <div class="input-group col-md-12 text-right flex">
        <div class="flex-column">
            <label class="control-label" for="question-img">Question image:</label>
            <button type="button" data-toggle="modal" data-target="#previewModal"
                    v-on:click="onModalCall(leftImageUrl)">
                <img :src="leftImageUrl" alt="left-img"  v-if="leftImageUrl !== ''"/>
            </button>
            <input type="file" name="question-img" id="question-img" :class="leftImageUrl ? '' : 'drop-area'"
                   accept="image/*" @change="previewImgUrl($event, 'left')">
            <span class="error-msg" v-if="error.questionImg === false">
                No image selected
            </span>
        </div>
        <div class="form-group col-md-12 text-left">
            <label class="control-label" for="answer">Answer:
            <input type="text" v-model="answerValue" :class="`form-control ${error.answer ? '' : 'error-msg'}`">
            </label>
            <span class="error-msg" v-if="error.answer === false">
                Please write an answer
            </span>
        </div>
        <div class="flex-column">
            <label class="control-label" for="explanation-img">Explanation image:</label>
            <button type="button" data-toggle="modal" data-target="#previewModal"
                    v-on:click="onModalCall(rightImageUrl)">
                <img :src="rightImageUrl" alt="right-img"  v-if="rightImageUrl !== ''"/>
            </button>
            <input type="file" @change="previewImgUrl($event, 'right')" :class="rightImageUrl ? '' : 'drop-area'"
                   name="explanation-img" accept="image/*">
            <span class="error-msg" v-if="error.explanationImg === false">
                No image selected
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                leftImageUrl: "",
                rightImageUrl: "",
                answerValue: "",
                questionImg: null,
                explanationImg: null
            }
        },
        methods: {
            previewImgUrl: function (event, target) {
                const vueInstance = this;
                const input = event.target;

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        if (target === "left") {
                            vueInstance.leftImageUrl = e.target.result;
                            vueInstance.questionImg = input.files[0];
                        } else if (target === "right") {
                            vueInstance.rightImageUrl = e.target.result;
                            vueInstance.explanationImg = input.files[0]
                        }
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        },
        props: ['onModalCall' , 'inputValues' , 'error'],
        watch: {
            answerValue() {
                this.$emit( 'update:answer' , this.answerValue)
            },
            questionImg() {
                this.$emit( 'update:questionImg' , this.questionImg)
            },
            explanationImg() {
                this.$emit( 'update:explanationImg' , this.explanationImg)
            },
        }
    }
</script>

<style scoped>
    .flex-column {
        width: 40%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .drop-area::after {
        content: "";
        display: block;
        margin-top: 15px;
        min-height: 150px;
        background-image: url("https://image.flaticon.com/icons/png/512/1589/1589085.png");
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
    }

    button {
        border: none;
    }

    img {
        max-width: 100%;
    }

    .error-msg {
        color: red;
        border-color: red;
    }
</style>

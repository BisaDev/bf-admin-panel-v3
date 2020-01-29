<template>
    <div class="col-md-12 text-right flex">
        <div class="flex-column">
            <label class="control-label" for="question-img">Question image:</label>
            <button type="button" class="modal-trigger" data-toggle="modal" data-target="#previewModal"
                    v-on:click="onModalCall(leftImageUrl)" v-if="leftImageUrl !== ''">
                <img :src="leftImageUrl" alt="left-img"/>
            </button>
            <div class="droppable" v-if="!uploaded">
                <span :class="leftImageUrl ? 'button-thin' : 'button-thick'">
                    Drag an image or click to browse
                </span>
                <input type="file" @change="previewImgUrl($event, 'left')"
                       name="question-img" accept="image/*">
            </div>
            <span class="error-msg" v-if="error.questionImg === false">
                No image selected
            </span>
        </div>
        <div class="form-group col-md-12 text-left">
            <label class="control-label" for="answer">Answer:</label>
            <input type="text" v-model="answerValue" v-if="!uploaded"
                   :class="`form-control ${error.answer ? '' : 'error-msg'}`">
            <p class="answer-display" v-if="uploaded">{{answerValue}}</p>
            <span class="error-msg" v-if="error.answer === false">
                Please write an answer
            </span>
        </div>
        <div class="flex-column">
            <label class="control-label" for="explanation-img">Explanation image:</label>
            <button type="button" class="modal-trigger" data-toggle="modal" data-target="#previewModal"
                    v-on:click="onModalCall(rightImageUrl)" v-if="rightImageUrl !== ''">
                <img :src="rightImageUrl" alt="right-img"/>
            </button>
            <div class="droppable" v-if="!uploaded">
                <span :class="rightImageUrl ? 'button-thin' : 'button-thick'">
                    Drag an image or click to browse
                </span>
                <input type="file" @change="previewImgUrl($event, 'right')"
                       name="explanation-img" accept="image/*">
            </div>
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
        props: ['onModalCall', 'inputValues', 'error', 'uploaded'],
        watch: {
            answerValue() {
                this.$emit('update:answer', this.answerValue)
            },
            questionImg() {
                this.$emit('update:questionImg', this.questionImg)
            },
            explanationImg() {
                this.$emit('update:explanationImg', this.explanationImg)
            },
        }
    }
</script>

<style scoped>
    .flex-column {
        width: 40%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .droppable {
        height: auto;
        color: #FFF;
        display: flex;
        outline: none;
        padding: 6px 12px;
        border-radius: 3px;
        text-align: center;
        position: relative;
        align-items: center;
        background: #5fbeaa;
        justify-content: center;
    }

    input[type="file"] {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        width: 100%;
        position: absolute;
    }

    .modal-trigger {
        padding: 0;
        background: none;
        overflow: hidden;
        max-height: 300px;
        margin-bottom: 5px;
    }

    .button-thin {
        margin: 15px 0;
    }

    .button-thick {
        margin: 40px 0;
    }

    button {
        border: none;
    }

    img {
        max-width: 100%;
    }

    .control-label {
        width: 100%;
        text-align: left;
    }

    .error-msg {
        color: red;
        border-color: red;
    }
</style>

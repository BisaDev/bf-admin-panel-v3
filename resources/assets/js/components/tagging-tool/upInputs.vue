<template>
    <div class="input-group col-md-12 text-right flex">
        <div class="flex-column">
            <label class="control-label" for="question-img">Question image:</label>
            <button type="button" data-toggle="modal" data-target="#previewModal"
                    v-on:click="onModalCall(leftImageUrl)">
                <img :src="leftImageUrl" alt="left-img"  v-if="leftImageUrl !== ''"/>
            </button>
            <input type="file" name="question-img" id="question-img"
                   accept="image/*" @change="previewImgUrl($event, 'left')">
        </div>
        <div class="form-group col-md-12 text-left">
            <label class="control-label" for="answer">Answer:
            <input type="text" v-model="answerValue" class="form-control">
            </label>
        </div>
        <div class="flex-column">
            <label class="control-label" for="explanation-img">Explanation image:</label>
            <button type="button" data-toggle="modal" data-target="#previewModal"
                    v-on:click="onModalCall(rightImageUrl)">
                <img :src="rightImageUrl" alt="right-img"  v-if="rightImageUrl !== ''"/>
            </button>
            <input type="file" @change="previewImgUrl($event, 'right')" name="explanation-img" accept="image/*">
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
        props: ['onModalCall' , 'inputValues'],
        watch: {
            answerValue() {
                console.log("text input");
                this.$emit( 'update:answer' , this.answerValue)
            },
            questionImg() {
                console.log("left upload!");
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

    button {
        border: none;
    }

    img {
        max-width: 100%;
    }

</style>

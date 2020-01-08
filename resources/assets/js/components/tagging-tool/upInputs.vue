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
            <input type="text" name="answer" v-model="answerValue" class="form-control">
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
                answerValue : "",
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
                        } else if (target === "right") {
                            vueInstance.rightImageUrl = e.target.result;
                        }
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        },
        props: ['onModalCall' , 'inputValues'],
        mounted: function () {
          console.log(this)
        },
        computed: {
            textInput() {
                return this.answerValue;
            }
        },
        watch: {
            textInput() {
                console.log("Changes text");
                this.$emit( 'update:answer' , this.answerValue)
            }
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
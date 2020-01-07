<template>
    <div class="input-group col-md-12 text-right flex">
        <div class="flex-column">
            <label class="control-label" for="question-img">Question image:</label>
            <button type="button" data-toggle="modal" data-target="#previewModal" v-on:click="onModalCall(leftImageUrl)">
                <img :src="leftImageUrl" alt="left-img" :id="`left-img${locator}`" v-if="leftImageUrl !== ''" />
            </button>
            <input type="file" :id="`left-${locator}`" name="question-img" accept="image/*">
        </div>
        <div class="form-group col-md-12 text-left">
            <label class="control-label" for="answer">Answer:</label>
            <input type="text" name="answer" class="form-control">
        </div>
        <div class="flex-column">
            <label class="control-label" for="answer-img">Answer image:</label>
            <button type="button" data-toggle="modal" data-target="#previewModal" v-on:click="onModalCall(rightImageUrl)">
                <img :src="rightImageUrl" alt="right-img" :id="`right-img${locator}`" v-if="rightImageUrl !== ''" />
            </button>
            <input type="file" :id="`right-${locator}`" name="answer-img" accept="image/*">
        </div>


    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                leftImageUrl: "",
                rightImageUrl: ""
            }
        },
        mounted() {
            const vueInstance = this;

            const leftInput = document.querySelector(`#left-${vueInstance.locator}`);
            const rightInput = document.querySelector(`#right-${vueInstance.locator}`);

            leftInput.addEventListener('change', function () {
                readURL(this, 'left')
            });
            rightInput.addEventListener('change', function () {
                readURL(this, 'right')
            });

            function readURL(input , target) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        if( target === "left") {
                            vueInstance.leftImageUrl = e.target.result;
                        } else  if (target === "right") {
                            vueInstance.rightImageUrl = e.target.result;
                        }
                    };


                    reader.readAsDataURL(input.files[0]);
                }
            }
        },
        props: ['locator' , 'onModalCall']
    }
</script>

<style scoped>
    .flex-column {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    button {
        border:none;
    }

    img {
        max-width: 100%;
    }

</style>
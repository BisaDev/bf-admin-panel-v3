<template>
    <div class="input-group col-md-12 text-right flex">
        <div class="flex-column">
            <button type="button" data-toggle="modal" data-target="#exampleModal" v-on:click="onModalCall(leftImageUrl)">
                <img :src="leftImageUrl" alt="left-img" :id="`left-img${locator}`"></img>
            </button>
            <input type="file" :id="`left-${locator}`" accept="image/*">
        </div>
        <input type="text" placeholder="dude">
        <div class="flex-column">
            <button type="button" data-toggle="modal" data-target="#exampleModal" v-on:click="onModalCall(rightImageUrl)">
                <img :src="rightImageUrl" alt="right-img" :id="`right-img${locator}`"></img>
            </button>
            <input type="file" :id="`right-${locator}`" accept="image/*">
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
            const that = this;

            const leftInput = document.querySelector(`#left-${that.locator}`);
            const rightInput = document.querySelector(`#right-${that.locator}`);

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
                            that.leftImageUrl = e.target.result;
                        } else  if (target === "right") {
                            that.rightImageUrl = e.target.result;
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
    }
    .modal-header {
        border: none;
    }

    button {
        border:none;
    }

</style>
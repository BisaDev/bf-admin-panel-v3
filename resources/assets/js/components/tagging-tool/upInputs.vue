<template>
    <div class="input-group col-md-12 text-right flex">
        <div class="flex-column">
            <img src="" alt="" :id="`left-img${locator}`"></img>
            <input type="file" :id="`left-${locator}`" accept="image/*">
        </div>
        <input type="text" placeholder="dude">
        <div class="flex-column">
            <img src="" alt="" :id="`right-img${locator}`"></img>
            <input type="file" :id="`right-${locator}`" accept="image/*">
        </div>
    </div>
</template>

<script>
    export default {
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
                    let image;

                    if( target === "left") {
                        image = document.querySelector(`#left-img${that.locator}`);
                    } else  if (target === "right") {
                        image = document.querySelector(`#right-img${that.locator}`);
                    }

                    reader.onload = function (e) {
                        image.src = e.target.result
                    };


                    reader.readAsDataURL(input.files[0]);
                }
            }
        },
        props: ['locator']
    }
</script>

<style scoped>
    .flex-column {
        display: flex;
        flex-direction: column;
    }

</style>
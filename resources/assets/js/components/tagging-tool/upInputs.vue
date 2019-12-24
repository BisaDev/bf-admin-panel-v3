<template>
    <div class="input-group col-md-12 text-right flex">
        <input type="file" :id="`left-${locator}`" accept="image/*">
        <input type="text" placeholder="dude">
        <input type="file" :id="`right-${locator}`" accept="image/*">
    </div>
</template>

<script>
    export default {
        mounted() {
            const that = this;

            const leftInput = document.querySelector(`#left-${that.locator}`);
            const rightInput = document.querySelector(`#right-${that.locator}`);

            leftInput.addEventListener('change', function () {
                console.log("Dude-left");
            })
            rightInput.addEventListener('change', function () {
                console.log("Dude-right");
            })
        },
        props: ['locator']
    }
</script>

<style scoped>

</style>
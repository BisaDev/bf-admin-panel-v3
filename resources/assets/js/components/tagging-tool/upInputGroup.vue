<template>
    <div class="card-box wrapper">
        <div class="input-group col-md-12 text-left text-left">
            <div class="flex">
                <div class="flex column">
                    <label for="subject">Subject:</label>
                    <select name="subject">
                        <option v-for="subject in subjects">
                            {{subject.name}}
                        </option>
                    </select>
                </div>

                <button type="button" @click="addInputs" class="btn btn-sm btn-default add-img-btn">
                    Add image
                    <span class="m-l-5">
                        <i class="fa fa-plus"></i>
                    </span>
                </button>
            </div>
        </div>

        <div v-for="index in images" :key="index">
            <up-inputs
                    :onModalCall="modalCall"
                    :locator="index">
            </up-inputs>
        </div>

        <div class="form-group col-md-12 text-right m-t-30">
            <button @click="handleClick(inputID)" class="btn btn-md btn-info">Cancel</button>
            <button type="submit" class="btn btn-md btn-primary">Upload</button>
        </div>
    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                images: [1]
            }
        },
        methods: {
            addInputs: function () {
                const {images} = this.$data;
                const randomId = Math.random().toString(36).substr(2, 9);
                images.push(randomId);
            }
        },
        props: ['inputID', 'handleClick', 'subjects','modalCall']

    }
</script>

<style scoped>
    .wrapper {
        display: flex;
        flex-direction: column;
    }

    .add-img-btn {
        margin-left: 50px;
    }

    .input-group {
        margin: 30px 0 10px;
    }

    .flex {
        display: flex;
        justify-content: space-between;
    }

    .column {
        flex-direction: column;
    }

</style>
<template>
    <div id="add-children">
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-sm btn-default" @click="addInput">
                Add upload
                <span class="m-l-5">
                    <i class="fa fa-plus"></i>
                </span>
            </button>
        </div>

        <div class="form-group col-md-12" v-for="index in questions" :key="index">
            <up-input-group
                    v-bind:subjects="subjects"
                    v-bind:inputID="index"
                    v-bind:handleClick="removeInput"
            >
            </up-input-group>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                questions: [1],
                subjects: null
            }
        },
        methods: {
            addInput: function () {
                const {questions} = this.$data;
                const randomId = Math.random().toString(36).substr(2, 9);
                questions.push(randomId);

            },
            removeInput: function (index) {
                const {questions} = this.$data;
                this.$data.questions = questions.filter(id => id !== index);
            }
        },
        mounted: function () {
            const url = "image-upload/getsubjects";
            var that = this;
            axios.get(url)
                .then(function (response) {
                    that.subjects = response.data;
                    console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                    console.log("Failed dudeee")
                })
        }
    }
</script>

<style scoped>

</style>
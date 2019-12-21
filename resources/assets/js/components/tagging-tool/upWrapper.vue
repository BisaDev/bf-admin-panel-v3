<template>
    <div id="add-children">
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-sm btn-default" @click="addInput">
                Add topic
                <span class="m-l-5">
                    <i class="fa fa-plus"></i>
                </span>
            </button>
        </div>

        <div class="form-group col-md-12" v-for="index in questions" :key="index">
            <p>dude</p>
            <p @click="removeInput(index)">{{index}}</p>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                questions: []
            }
        },
        methods: {
            addInput: function () {
                const {questions} = this.$data;
                const randomId =  Math.random().toString(36).substr(2, 9);
                questions.push(randomId);

            },
            removeInput: function (index) {
                const {questions} = this.$data;
                this.$data.questions = questions.filter(id => id !== index);
            }
        }
    }
</script>

<style scoped>

</style>
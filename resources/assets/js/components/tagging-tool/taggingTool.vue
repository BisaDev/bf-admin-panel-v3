<template>
    <div class="col-sm-12 tagging-tool" v-if="questionsToTag">
        <div class="card-box" v-for="(question, index) in questionsToTag" v-if="tagCount === index">
            <div class="image-display">
                <h2>{{question.id}}</h2>
                <h2>{{index}}</h2>
                <div class="topic-display"></div>
                <button  class="btn btn-info" @click="tagCount++">
                    Next question
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                tagCount: 0,
                questionsToTag: null,
            }
        },
        mounted: function () {
            const vueInstance = this;
            const subjectID = this.subject_id;
            const questionUrl = `${this.tagging_route}/question/${subjectID}`;
            console.log(this.topics_route);

            axios.get(questionUrl)
                .then(function (response) {
                    console.log(response);
                    vueInstance.questionsToTag = response.data;
                })
                .catch(function(err){
                    console.log(err)
                })

        },
        props: {
            'subject_id' : String,
            'topics_route' : String,
            'tagging_route' : String
        }
    }
</script>

<style scoped>
    .image-display {
        display: flex;
        justify-content: space-between;
    }

    .topic-display {
        display: flex;
        flex-direction: column;
    }
</style>

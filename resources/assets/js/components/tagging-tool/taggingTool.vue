<template>
    <div class="col-sm-12 tagging-tool" v-if="questionsToTag">
        <div class="card-box" v-for="(question, index) in questionsToTag" v-if="tagCount === index">
            <div class="image-display">
                <img :src="'storage/SAT-%20Algebra-answerb-explanationImg_0.png'"
                     :alt="`${question.image.image_url}`">
                <div class="topic-display">
                    <div v-for="topic in topicsList">
                        <button>
                            {{topic.name}}
                        </button>
                    </div>
                </div>
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
                topicsList: null,
                questionsToTag: null,
            }
        },
        mounted: function () {
            const vueInstance = this;
            const subjectID = this.subject_id;
            const questionUrl = `${this.questions_route}/${subjectID}`;
            const topicsUrl = `${this.topics_route}/${subjectID}`;

            axios.get(questionUrl)
                .then(function (response) {
                    vueInstance.questionsToTag = response.data;

                    axios.get(topicsUrl)
                        .then(function (response) {
                            vueInstance.topicsList = response.data;
                            console.log(response.data)
                        })
                        .catch(function(err){
                            console.log(err)
                        })
                })
                .catch(function(err){
                    console.log(err)
                })

        },
        props: {
            'subject_id' : String,
            'topics_route' : String,
            'questions_route' : String
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

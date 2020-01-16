<template>
    <div class="col-sm-12 tagging-tool" v-if="questionsToTag">
        <div class="card-box" v-for="(question, index) in questionsToTag" v-if="tagCount === index">
            <div class="image-display">
                <img class="tag-image"
                     :src="`${question.image.image_url}`"
                     :alt="`${question.image.image_url}`">
                <div class="topic-display">
                    <button class="topic-item btn" v-for="topic in topicsList">
                        {{topic.name}}
                    </button>
                </div>
                <button class="btn btn-info" @click="tagCount++">
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
                        .catch(function (err) {
                            console.log(err)
                        })
                })
                .catch(function (err) {
                    console.log(err)
                })

        },
        props: {
            'subject_id': String,
            'topics_route': String,
            'questions_route': String
        }
    }
</script>

<style scoped>
    .image-display {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .topic-display {
        display: flex;
        flex-direction: column;
        width: 35%;
    }

    .topic-item {
        width: 100%;
    }

    .topic-item:nth-child(odd) {
        background-color: #eeeeee;
    }

    .topic-item:nth-child(even) {
        color: wheat;
        background-color: #336ca2;
    }

    .topic-item:nth-child(even):hover {
        color: white;
    }

    .tag-image {
        width: 35%;
    }
</style>

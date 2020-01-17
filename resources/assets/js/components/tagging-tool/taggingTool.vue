<template>
    <div class="col-sm-12 tagging-tool">
        <div class="card-box" v-for="(question, index) in questionsToTag"
             v-if="questionsToTag && tagCount === index">
            <div class="image-display">
                <img class="tag-image"
                     :src="`${question.image.image_url}`"
                     :alt="`${question.image.image_url}`">
                <div class="topic-display">
                    <button class="topic-item btn" v-for="topic in topicsList"
                            @click="handleTagging(topic.id,question.id)">
                        {{topic.name}}
                    </button>
                </div>
                <button class="btn btn-info btn-next" @click="nextQuestion">
                    Next question
                </button>
            </div>
        </div>
        <div class="card-box text-center image-display" v-if="!questionsToTag">
            <h2 class="success-text">All questions tagged !</h2>
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
        methods: {
            handleTagging: function (topic, question) {
                const vueInstance = this;
                const url = `${this.tagging_route}/${topic}`;
                const payload = {
                    topic_id: topic,
                    question_id: question,
                };

                axios.post(url, payload)
                    .then(function () {
                        vueInstance.nextQuestion()
                    })
                    .catch(function (error) {
                        console.log(error)
                    })
            },
            nextQuestion: function () {
                let {tagCount} = this;
                let maxCount;
                this.questionsToTag
                    ? maxCount = this.questionsToTag.length - 1
                    : maxCount = 0;

                if (tagCount < maxCount) {
                    this.tagCount++;
                    console.log(tagCount + "/" + this.questionsToTag.length)
                } else if (tagCount === maxCount) {
                    console.log("Max count reached");
                    this.questionsToTag = null;
                }
            }
        },
        mounted: function () {
            const vueInstance = this;
            const subjectID = this.subject_id;
            const questionUrl = `${this.questions_route}/${subjectID}`;
            const topicsUrl = `${this.topics_route}/${subjectID}`;

            axios.get(questionUrl)
                .then(function (response) {
                    if (response.data.length > 0) {
                        vueInstance.questionsToTag = response.data;

                        axios.get(topicsUrl)
                            .then(function (response) {
                                vueInstance.topicsList = response.data;
                            })
                            .catch(function (err) {
                                console.log(err)
                            })
                    }

                })
                .catch(function (err) {
                    console.log(err)
                })

        },
        props: {
            'subject_id': String,
            'topics_route': String,
            'tagging_route': String,
            'questions_route': String
        }
    }
</script>

<style scoped>
    .image-display {
        width: 100%;
        display: flex;
        min-height: 556px;
        align-items: center;
        justify-content: space-between;
    }

    .topic-display {
        width: 35%;
        display: flex;
        flex-direction: column;
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

    .btn-next {
        height: 80px;
    }

    .success-text {
        min-width: 100%;
    }
</style>

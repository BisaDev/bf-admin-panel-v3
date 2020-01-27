<template>
    <div class="col-sm-12 tagging-tool">
        <div class="card-box" v-for="(question, index) in questionsToTag"
             v-if="questionsToTag && tagCount === index">
            <div class="image-display">
                <img class="tag-image"
                     :src="`${question.image.imageFile}`"
                     :alt="`${question.image.image_url}`">
                <div class="topic-display">
                    <button class="topic-item btn" v-for="topic in topicsList"
                            @click="handleTagging(topic.id,question.id)">
                        {{topic.name}}
                    </button>
                </div>
            </div>
            <div class="text-right">
                <a @click="nextQuestion">
                    Next question
                </a>
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
            handleTagging: function (topic_id, question_id ) {
                const vueInstance = this;
                const payload = {
                    topic_id, question_id, subject_id: this.subject_id
                };

                axios.post(this.tagging_route, payload)
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
            },
            getQuestions () {
                const vueInstance = this;
                const subjectID = this.subject_id;
                const questionUrl = `${this.questions_route}/${subjectID}`;

                axios.get(questionUrl)
                    .then(function (response) {
                        if (response.data.length > 0) {
                            const {data} = response;
                            vueInstance.questionsToTag = data[0];
                            vueInstance.topicsList = data[1];
                        }

                    })
                    .catch(function (err) {
                        console.log(err)
                    })
            }
        },
        mounted: function () {
            this.getQuestions()
        },
        props: {
            'subject_id': String,
            'tagging_route': String,
            'questions_route': String
        }
    }
</script>


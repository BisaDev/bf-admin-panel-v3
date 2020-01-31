<template>
    <div class="col-sm-12 tagging-tool">
        <div class="card-box" v-for="(question, index) in questionsToTag"
             v-if="questionsToTag && tagCount === index">
            <a @click="cycleQuestion('back')" v-if="tagCount !== 0">
                Back
            </a>
            <div class="image-display">
                <div class="tag-image">
                    <img class="tag-display"
                         :src="currentDisplay"
                         :alt="currentDisplay">
                </div>
                <div class="topic-display">
                    <button class="topic-item btn" v-for="topic in subject.topics"
                            @click="handleTagging(topic.id,question.id)">
                        {{topic.name}}
                    </button>
                </div>
            </div>
            <div class="lower-group">
                <div class="image-selector-group" v-if="question.image.length > 1">
                    <img class="preview-thumbnail" v-for="(image,index) in question.image"
                         :src="image.questionFileUrl" alt="" @click="updateMainDisplay(index)">
                </div>
                <div class="skip-link text-right">
                    <a @click="cycleQuestion('next')">
                        Skip
                    </a>
                </div>
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
                currentDisplay: null,
                questionsToTag: null,
            }
        },
        methods: {
            handleTagging: function (topic_id, question_id) {
                const vueInstance = this;
                const payload = {
                    topic_id, question_id, instructor_id: this.current_user
                };

                axios.post(this.tagging_route, payload)
                    .then(function () {
                        vueInstance.cycleQuestion("next")
                    })
                    .catch(function (error) {
                        console.log(error)
                    })
            },
            updateMainDisplay: function (index) {
                this.currentDisplay = this.questionsToTag[this.tagCount].image[index].questionFileUrl

            },
            cycleQuestion: function (type) {
                let {tagCount} = this;
                let maxCount;
                this.questionsToTag
                    ? maxCount = this.questionsToTag.length - 1
                    : maxCount = 0;

                if (tagCount < maxCount) {
                    if(type === "next") {
                        this.tagCount++;
                    } else {
                        this.tagCount--;
                    }
                    this.updateMainDisplay(0);
                } else if (tagCount === maxCount) {
                    this.questionsToTag = null;
                }

            },
            getQuestions() {
                const vueInstance = this;
                const subjectID = this.subject.id;
                const questionUrl = `${this.questions_route}/${subjectID}`;

                axios.get(questionUrl)
                    .then(function (response) {
                        vueInstance.questionsToTag = response.data;
                        console.log(vueInstance.questionsToTag)
                        vueInstance.currentDisplay = vueInstance.questionsToTag[0].image[0].questionFileUrl
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
            'subject': Object,
            "current_user": Number,
            'tagging_route': String,
            'questions_route': String
        }
    }
</script>


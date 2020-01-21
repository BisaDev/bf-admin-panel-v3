<template>
    <div class="row image-download">
        <div class="col-sm-12">
            <div class="card-box">
                <div>
                    <label class="filter-input">Subject:
                        <select v-model="currentSelection.subject" name="subject" class="form-control"
                                @change="getTopicsList($event)">
                            <option value="">Select subject</option>
                            <option v-for="(subject, index) in subjects" :value="index">
                                {{subject.name}}
                            </option>
                        </select>
                    </label>

                    <label class="filter-input">Topic:
                        <select v-model="currentSelection.topic" name="subject" class="form-control"
                                @change="getQuestions($event)">
                            <option value="">Select topic</option>
                            <option v-for="topic in topicsList" :value="topic.id">
                                {{topic.name}}
                            </option>
                        </select>
                    </label>
                </div>
                <table class="table table-responsive table-hover model-list" v-if="results">
                    <thead>
                    <tr>
                        <th width="300">Question Image</th>
                        <th width="300" class="text-center">Answer</th>
                        <th width="300" class="text-center">Answer explanation</th>
                        <th width="110">Download Zip</th>
                    </tr>

                    </thead>
                    <tbody>
                        <tr v-for="result in results">
                            <th width="300">
                                <img :src="result.image.image_url" :alt="result.tagging_topic_id">
                            </th>
                            <th width="300" class="text-center">{{result.image.image_answer}}</th>
                            <th width="300" class="text-center">
                                <img :src="result.image.explanation_url" :alt="result.tagging_topic_id">
                            </th>
                            <th width="110">Download Zip</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                results: null,
                topicsList: null,
                currentSelection: {
                    subject: null,
                    topic: null
                },

            }
        },
        methods: {
            getTopicsList: function (selection) {
                this.results = null;
                this.currentSelection.topic = null;
                const inputIndex = selection.target.value;

                this.topicsList = this.subjects[inputIndex].topics;
            },
            getQuestions: function (event) {
                const topicId = event.target.value;
                const url = `${this.question_route}/${topicId}`;
                const vueInstance = this;
                axios.get(url)
                    .then(function (response) {
                        vueInstance.results = response.data;
                        console.log(response.data)

                    })
                    .catch(function (err) {
                        console.log(err)
                    })
            }
        },
        mounted() {
            console.log(this.question_route);
            console.log(this.subjects)
        },
        props: {
            'subjects': Array,
            'question_route': String
        }
    }
</script>


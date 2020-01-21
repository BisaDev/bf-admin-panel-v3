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
                        <select v-model="currentSelection.topic" name="subject" class="form-control" @change="getQuestions($event)">
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
                        <th width="300">Image</th>
                        <th width="300" class="text-center">Subject</th>
                        <th width="300" class="text-center">Answer</th>
                        <th width="110">Download Zip</th>
                    </tr>
                    </thead>
                    <tbody>

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
            getTopicsList : function (selection) {
                this.results = null;
                this.currentSelection.topic = null;
                const inputIndex = selection.target.value;

                this.topicsList = this.subjects[inputIndex].topics;
            },
            getQuestions: function (event) {
                const topicId = event.target.value;
                console.log(topicId);
            }
        },
        mounted() {
            console.log(this.subjects)
        },
        props: {
            'subjects': Array,
        }
    }
</script>


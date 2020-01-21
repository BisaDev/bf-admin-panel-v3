<template>
    <div class="row image-download">
        <div class="col-sm-12">
            <div class="card-box">
                <div>
                    <label class="filter-input">Subject:
                        <select v-model="currentSelection.subject" name="subject" class="form-control" @change="getTopicsList($event)">
                            <option value="">Select subject</option>
                            <option v-for="(subject, index) in subjects" :value="index">
                                {{subject.name}}
                            </option>
                        </select>
                    </label>

                    <label class="filter-input">Topic:
                        <select v-model="currentSelection.topic" name="subject" class="form-control">
                            <option value="">Select topic</option>
                            <option v-for="(topics, index) in topicsList" :value="index">
                                {{topics.name}}
                            </option>
                        </select>
                    </label>
                </div>
                <table class="table table-responsive table-hover model-list">
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
                currentSelection: {
                    subject: null,
                    topic: null
                },
                topicsList: null

            }
        },
        methods: {
            getTopicsList : function (selection) {
                const inputIndex = selection.target.value;
                this.topicsList = this.subjects[inputIndex].topics;
                this.currentSelection.topic = null;
            }
        },
        mounted() {
            console.log(this.subjects)
        },
        props: {
            'subjects': Array,
            'topics_route': String,
        }
    }
</script>


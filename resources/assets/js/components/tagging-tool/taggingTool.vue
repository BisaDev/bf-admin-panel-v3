<template>
    <div id="index-container" class="row">
        <div class="col-sm-12" v-if="!questionToTag">
            <div class="card-box">
                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th width="300">Subject</th>
                        <th width="300" class="text-center">Tagged Questions</th>
                        <th width="300" class="text-center">Untagged</th>
                        <th width="200"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="subject in subjects">
                        <th>{{ subject.name }}</th>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                        <td class="text-center">
                            <button @click="getQuestion" :id="subject.id" class="btn btn-info">
                                Start Tagging
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6" v-if="!questionToTag">
            <div class="card-box">
                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th width="400">Instructor</th>
                        <th width="200">Tagged Questions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="instructor in instructors">
                        <th>{{ instructor.name }}</th>
                        <td>0</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-12 tagging-tool" v-if="questionToTag">
            <div class="card-box">
                <p>Image to display</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                questionToTag: ""
            }
        },
        props: {
            "subjects": Array,
            "instructors": Array,
            "tagging_route": String
        },
        methods: {
            getQuestion: function (event) {
                const vueInstance = this;
                const subjectID = event.toElement.id;
                const url = `${this.tagging_route}/question/${subjectID}`;

                axios.get(url)
                .then(function (response) {
                    console.log(...response.data);
                    vueInstance.questionToTag = response.data;
                })
                .catch(function(err){
                    console.log(err)
                })
            }
        }
    }
</script>

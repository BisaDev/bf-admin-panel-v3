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
                        <th width="200" class="text-center">Answer</th>
                        <th width="300" class="text-center">Answer explanation</th>
                        <th width="50" class="text-center">PDF</th>
                        <th width="50" class="text-center">Download Zip</th>
                    </tr>

                    </thead>
                    <tbody>
                    <tr v-for="(result,index) in results">
                        <th>
                            <button type="button" data-toggle="modal" data-target="#previewModal"
                                    @click="updatePreviewModal(result.image.imageFile)">
                                <img class="img-preview" :src="result.image.imageFile"
                                     :alt="result.tagging_topic_id">
                            </button>
                        </th>
                        <th class="down-input-group">{{result.image.image_answer}}</th>
                        <th>
                            <button type="button" data-toggle="modal" data-target="#previewModal"
                                    @click="updatePreviewModal(result.image.explanationFile)">
                                <img class="img-preview" :src="result.image.explanationFile"
                                     :alt="result.tagging_topic_id">
                            </button>
                        </th>
                        <th class="down-input-group" >
                            <input type="text" :value="index+1" @change="updateSelectedQuestion($event)">
                        </th>
                        <th class="down-input-group" >
                            <input type="checkbox" :value="index" @change="updateSelectedQuestion($event)">
                        </th>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-info btn-zip" @click="handleDownload">
                    Download zip
                </button>
                <a id="download-link" :href="downloadLink.href" download="file.zip">a</a>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img class="modal-img" :src="modalImageUrl" alt="modal-img"/>
                    </div>
                </div>
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
                downloadLink: {
                    href: "",
                    name: "file.jpg"
                },
                modalImageUrl: "",
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

                    })
                    .catch(function (err) {
                        console.log(err)
                    })
            },
            updatePreviewModal: function (imageUrl) {
                this.modalImageUrl = imageUrl
            },
            handleDownload: function () {
                const vueInstance = this;
                const imageFile = this.results[0].image.imageFile;
                const explanationFile = this.results[0].image.explanationFile;

                const url = `${this.download_route}`;

                const config = {
                    responseType: 'blob',
                    params: {
                        imageFile,
                        explanationFile
                    }
                };

                axios.get(url , config)
                    .then(function (response) {
                        console.log(response.data);
                        vueInstance.downloadLink.href = window.URL.createObjectURL(new Blob([response.data]));
                    })
                    .catch(function (err) {
                        console.log(err);
                    })
            },
            updateSelectedQuestion (event) {
                console.log(event)
            }
        },
        props: {
            'subjects': Array,
            'question_route': String,
            'download_route': String
        }
    }
</script>


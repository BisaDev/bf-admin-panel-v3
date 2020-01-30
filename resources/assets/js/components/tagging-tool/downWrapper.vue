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
                        <th width="10">Question Image</th>
                        <th width="10" class="text-center">Answer explanation</th>
                        <th width="600" class="text-center">Answer</th>
                        <th width="50" class="text-center">PDF</th>
                        <th width="50" class="text-center">Download Zip</th>
                    </tr>

                    </thead>
                    <tbody>
                    <tr v-for="(result,index) in results">
                        <th>
                            <button type="button" data-toggle="modal" data-target="#previewModal"
                                    class="preview-btn" @click="updatePreviewModal(result.imageFile)">
                                <img class="img-preview" :src="result.imageFile"
                                     :alt="result.tagging_topic_id">
                            </button>
                        </th>
                        <th>
                            <button type="button" data-toggle="modal" data-target="#previewModal"
                                    class="preview-btn" @click="updatePreviewModal(result.explanationFile)">
                                <img class="img-preview" :src="result.explanationFile"
                                     :alt="result.tagging_topic_id">
                            </button>
                        </th>
                        <th class="down-input-group">{{result.image.image_answer}}</th>
                        <th class="down-input-group">
                            <input type="number" value=1 v-model="results[index].pdf">
                        </th>
                        <th class="down-input-group">
                            <input type="checkbox" v-model="results[index].checked"
                                   @change="updateSelectedQuestion($event)">
                        </th>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-info btn-zip" @click="handleDownload">
                    Download zip
                </button>
                <a id="download-link" :href="downloadLink.href" download="file.zip" hidden>a</a>
            </div>
        </div>

        <preview-modal
                :modalImageUrl="modalImageUrl"
        />
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
                    name: ""
                },
                modalImageUrl: "",
                currentSelection: {
                    subject: null,
                    topic: null
                },
                source: null

            }
        },
        methods: {
            getTopicsList: function (selection) {
                this.results = null;
                this.currentSelection.topic = null;
                const inputIndex = selection.target.value;

                this.topicsList = this.subjects[inputIndex].topics;
                this.source = `${this.subjects[inputIndex].name}_${this.topicsList[inputIndex].name}`
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
                const url = `${this.download_route}`;

                if (this.results) {
                    let payload = {};
                    this.results.forEach(result => {
                        if (result.checked && result.pdf) {
                            if (payload[`pdf${result.pdf}`]) {
                                payload[`pdf${result.pdf}`].push(result);
                            } else {
                                payload[`pdf${result.pdf}`] = [];
                                payload[`pdf${result.pdf}`].push(result);
                            }

                        }
                    });

                    const config = {
                        responseType: 'blob',
                        params: {
                            source: this.source,
                            payload
                        }
                    };

                    axios.get(url, config)
                        .then(function (response) {
                            const downloadTrigger = document.getElementById('download-link');
                            vueInstance.downloadLink.href = window.URL.createObjectURL(new Blob([response.data]));
                            downloadTrigger.click();
                        })
                        .catch(function (err) {
                            console.log(err);
                        })

                }
            },
            updateSelectedQuestion(event) {

            }
        },
        props: {
            'subjects': Array,
            'question_route': String,
            'download_route': String
        }
    }
</script>


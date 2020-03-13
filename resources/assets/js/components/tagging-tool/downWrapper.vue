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
                            <option v-for="(topic,index) in topicsList" :value="index">
                                {{topic.name}}
                            </option>
                        </select>
                    </label>
                </div>
                <table class="table table-responsive table-hover model-list" v-if="results">
                    <thead>
                    <tr>
                        <th width="10" class="text-center">Question Image</th>
                        <th width="10" class="text-center">Answer explanation</th>
                        <th width="600" class="text-center">Answer</th>
                        <th width="50" class="text-center">PDF</th>
                        <th width="50" class="text-center">Download Zip</th>
                    </tr>

                    </thead>
                    <tbody>
                    <tr v-for="(result,index) in results">
                        <th class="text-center">
                            <button type="button" data-toggle="modal" data-target="#previewModal"
                                    class="preview-btn" @click="updatePreviewModal(result.image[0].questionFileUrl)">
                                <img class="img-preview" :src="result.image[0].questionFileUrl"
                                     :alt="result.tagging_topic_id">
                            </button>
                        </th>
                        <th class="text-center">
                            <button type="button" data-toggle="modal" data-target="#previewModal" v-if="result.image[0].explanation_url !== ''"
                                    class="preview-btn" @click="updatePreviewModal(result.image[0].explanationFileUrl)">
                                <img class="img-preview" :src="result.image[0].explanationFileUrl"
                                     :alt="result.tagging_topic_id">
                            </button>
                        </th>
                        <th class="down-input-group">{{result.image[0].image_answer}}</th>
                        <th class="down-input-group">
                            <input type="number" value=1 v-model="results[index].pdf_id">
                        </th>
                        <th class="down-input-group">
                            <input type="checkbox" v-model="results[index].checked">
                        </th>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-info btn-zip" @click="handleDownload">
                    Download zip
                </button>
                <a id="download-link" :href="downloadLink.href" download="file.zip"/>
                <h4 class="error-msg" v-if="error">
                    {{ error }}
                </h4>
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
                source: null,
                error: '',

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
                const i = event.target.value;
                const topicId = this.topicsList[i].id;
                const url = `${this.question_route}/${topicId}`;
                const vueInstance = this;

                axios.get(url)
                    .then(function (response) {
                        vueInstance.results = response.data;
                        console.log(vueInstance.results)
                        const {currentSelection, subjects, topicsList} = vueInstance;
                        const subjectI = currentSelection.subject;
                        const topicI = currentSelection.topic;

                        vueInstance.source = `${subjects[subjectI].name}_${topicsList[topicI].name}`
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
                vueInstance.error = '';

                if (this.results) {
                    let payload = {};
                    let noCheckedItems = true;

                    this.results.forEach(result => {
                        if(result.checked && !result.pdf_id) {
                            noCheckedItems = false;
                            vueInstance.error = "A PDF Number must be provided for all checked items";
                        }

                        if (result.checked && result.pdf_id) {
                            noCheckedItems = false;

                            if (payload[`pdf${result.pdf_id}`]) {
                                payload[`pdf${result.pdf_id}`].push(result);
                            } else {
                                payload[`pdf${result.pdf_id}`] = [];
                                payload[`pdf${result.pdf_id}`].push(result);
                            }

                        }
                    });

                    if(noCheckedItems) {
                        vueInstance.error = "Please Select Items";
                    }

                    const config = {
                        responseType: 'blob',
                        params: {
                            source: this.source,
                            payload
                        }
                    };

                    if(!vueInstance.error) {
                        axios.get(url, config)
                            .then(function (response) {
                                vueInstance.downloadLink.href = window.URL.createObjectURL(new Blob([response.data]));
                                const downloadTrigger = document.getElementById('download-link');
                                setTimeout(() => {
                                    downloadTrigger.click();
                                }, 0)
                            })
                            .catch(function (err) {
                                console.log(err);
                                vueInstance.error = "There was an error creating the zip file";
                            })
                    } else {
                        return false;
                    }

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


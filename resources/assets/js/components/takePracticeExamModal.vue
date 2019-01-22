<template>
    <div>
        <div class="modal-body">
            <div class="form-group">
                <label for="exam-type">Select Exam Type:</label>
                <select class="form-control" id="exam-type" v-model="examType">
                    <option v-for="examType in examTypes">{{examType.type}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="test-id">Enter Test ID:</label>
                <select class="form-control" name="test-id" id="test-id" v-model="examId">
                    <option v-if="examType === exam.type" v-for="exam in exams">{{exam.test_id}}</option>
                </select>
            </div>

            <div v-show="examSection && !miniExam">
                <label class="col-md-offset-2"> Exam Section: </label> <br>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <ul class="list-group">
                            <li class="list-group-item" v-for="section in selectedTypeSections">
                                <div class="custom-control custom-checkbox">
                                    <label> <input type="checkbox" v-model="selected" :name="'sections[]'" :value="section.section_number"> {{section.section_name}} </label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <label><input type="checkbox" v-model="selectAll"> All sections </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" v-on:click="examSection = !examSection" v-if="!examSection" class="btn btn-md btn-info">Next</button>
            <button type="submit" v-if="examSection || miniExam" class="btn btn-md btn-info">Start Exam</button>
        </div>
    </div>
</template>

<script>
    import swal from "sweetalert2";

    export default {
        props: ['exams', 'examTypes', 'allSections', 'studentExams'],
        data() {
            return {
                selected: [],
                examType: '',
                examId: '',
                examSection: false,
                miniExam: false,
                selectedTypeSections: [],
            }
        },
        computed: {
            selectAll: {
                get: function () {
                    return this.selectedTypeSections ? this.selected.length === this.selectedTypeSections.length : false;
                },
                set: function (value) {
                    let selected = [];
                    if (value) {
                        this.selectedTypeSections.forEach(function (section) {
                            selected.push(section.section_number);
                        });
                    }
                    this.selected = selected;
                }
            }
        },
        watch: {
            examType: function() {
                let selectedTypeSections = [];
                let examType = this.examType;
                if (examType !== 'ACT' && examType !== 'SAT') {
                    this.miniExam = true;
                    this.examSection = true;
                } else {
                    this.miniExam = false;
                    this.examSection = false;
                    this.allSections.forEach(function (section) {
                        if (section.exam_type === examType) {
                            selectedTypeSections.push(section);
                        }
                    });
                    this.selectedTypeSections = selectedTypeSections;
                }
            }
        }
    }
</script>

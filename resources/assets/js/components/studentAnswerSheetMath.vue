<template>
    <div class="question col-md-2">
        <div class="question-number-math">{{ num }}</div><br>
        <div class="answer-math-2">
            <div class="col-md-3 answer-math-1">{{picked_1}}&nbsp;</div>
            <div class="col-md-3 answer-math-1">{{picked_2}}&nbsp;</div>
            <div class="col-md-3 answer-math-1">{{picked_3}}&nbsp;</div>
            <div class="col-md-3 answer-math-1">{{picked_4}}&nbsp;</div>
        </div>
        <div class="form-check question">
            <span class="student-label answer-slash">/</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="/" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="/" v-model="picked_3">
        </div>
        <div class="form-check question">
            <span class="student-label">.</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="." v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="." v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="." v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="." v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">0</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="0" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="0" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="0" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="0" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">1</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="1" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="1" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="1" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="1" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">2</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="2" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="2" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="2" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="2" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">3</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="3" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="3" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="3" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="3" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">4</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="4" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="4" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="4" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="4" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">5</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="5" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="5" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="5" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="5" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">6</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="6" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="6" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="6" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="6" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">7</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="7" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="7" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="7" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="7" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">8</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="8" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="8" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="8" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="8" v-model="picked_4">
        </div>
        <div class="form-check question">
            <span class="student-label">9</span>
            <input class="form-check-input student-answer" type="radio" :name="'question_1_' + num" value="9" v-model="picked_1">
            <input class="form-check-input student-answer" type="radio" :name="'question_2_' + num" value="9" v-model="picked_2">
            <input class="form-check-input student-answer" type="radio" :name="'question_3_' + num" value="9" v-model="picked_3">
            <input class="form-check-input student-answer" type="radio" :name="'question_4_' + num" value="9" v-model="picked_4">
        </div>
        <div class="form-check question">
            <button type="button" class="btn btn-sm btn-primary" @click="uncheck">Clear</button>
        </div>
        <input type="hidden" :name="'question_' + num" :value="picked_1 + picked_2 + picked_3 + picked_4">
    </div>
</template>

<script>
    export default {
        props: ['num'],
        data() {
            return {
                numberOfColumns: 5,
                picked_1: "",
                picked_2: "",
                picked_3: "",
                picked_4: "",
            }
        },
        computed: {
            rowCount: function () {
                return Math.floor(((this.questions - 1) / this.numberOfColumns)) + 1
            }
        },
        methods: {
            layoutCount: function (rows, columns) {
                return (rows - 1) * this.numberOfColumns + columns
            },
            uncheck: function () {
              this.picked_1 = "";
              this.picked_2 = "";
              this.picked_3 = "";
              this.picked_4 = "";
            },
        }
    }
</script>
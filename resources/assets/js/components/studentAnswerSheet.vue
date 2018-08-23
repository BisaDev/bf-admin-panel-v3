<template>
    <div class="container col-md-offset-1">
        <div class="row text-center">
            <div class="col-md-2" v-for="column in numberOfColumns">
                <div class="question" v-for="row in rowsPerColumn(column)">
                    <div class="question-number">{{ num = (column-1)*rowsPerColumn(column-1) + row }}</div>
                    <template v-if="!answers[0]">
                        <label class="form-check-label" :for="'question_' + num"> A <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="a"></label>
                        <label class="form-check-label" :for="'question_' + num"> B <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="b"></label>
                        <label class="form-check-label" :for="'question_' + num"> C <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="c"></label>
                        <label class="form-check-label" :for="'question_' + num"> D <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="d"></label>
                        <label class="form-check-label" v-if="row === 1" data-toggle="popover"><i class="ti-help-alt"></i> <input class="student-answer radio-inline" type="checkbox" :name="'guessed_' + num" value="1"></label>
                        <label class="form-check-label" v-if="row !== 1"> &nbsp; <input class="student-answer radio-inline" type="checkbox" :name="'guessed_' + num" value="1"></label>
                    </template>
                    <template v-else>
                        <label class="form-check-label" :for="'question_' + num"> A <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="a" :checked="(answers[num-1].correct_1 === 'a') ? true : false"></label>
                        <label class="form-check-label" :for="'question_' + num"> B <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="b" :checked="(answers[num-1].correct_1 === 'b') ? true : false"></label>
                        <label class="form-check-label" :for="'question_' + num"> C <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="c" :checked="(answers[num-1].correct_1 === 'c') ? true : false"></label>
                        <label class="form-check-label" :for="'question_' + num"> D <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="d" :checked="(answers[num-1].correct_1 === 'd') ? true : false"></label>
                    </template>
                </div>
            </div>
            <div v-if="!answers[0]" class="col-md-1 question text-right">
                <i class="ti-help-alt guessed-question" data-toggle="popover" title="Guessed" data-content="If you guessed the question, please mark its checkbox"></i>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['questions', 'answers'],
        data () {
            return {
                numberOfColumns : 5,
                remainder : this.questions % 5,
                maxRows : Math.floor(((this.questions - 1) / 5)) + 1,
            }
        },
        methods: {
            rowsPerColumn : function (column) {
                if(column === 5 && this.remainder > 0) {
                    return this.maxRows + (this.remainder - 5)
                } else {
                    return this.maxRows
                }
            },
        },
        mounted() {
            $('[data-toggle="popover"]').popover();
        },
    }
</script>

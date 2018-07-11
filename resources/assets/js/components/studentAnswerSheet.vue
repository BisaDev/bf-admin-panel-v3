<template>
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3" v-for="column in numberOfColumns">
                <div class="has-feedback" v-for="row in rowsPerColumn(column)">
                    <div class="question-number">{{ num = (column-1)*rowsPerColumn(column-1) + row }}</div>
                    <label class="form-check-label" :for="'question_' + num"> A <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="A"></label>
                    <label class="form-check-label" :for="'question_' + num"> B <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="B"></label>
                    <label class="form-check-label" :for="'question_' + num"> C <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="C"></label>
                    <label class="form-check-label" :for="'question_' + num"> D <input class="student-answer radio-inline" type="radio" :name="'question_' + num" value="D"></label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['questions'],
        data () {
            return {
                numberOfColumns : 4,
                remainder : this.questions % 4,
                maxRows : Math.floor(((this.questions - 1) / 4)) + 1,
            }
        },
        methods: {
            rowsPerColumn : function (column) {
                if(column === 4) {
                    if (this.remainder === 1){
                        return this.maxRows - 3
                    } else if (this.remainder === 3){
                        return this.maxRows - 1
                    } else {
                        return this.maxRows - this.remainder
                    }
                } else {
                    return this.maxRows
                }
            },
        }
    }
</script>

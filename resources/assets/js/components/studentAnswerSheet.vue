<template>
    <div class="container col-md-offset-1">
        <div class="row text-center">
            <div class="col-md-2" v-for="column in numberOfColumns">
                <div class="question" v-for="row in rowsPerColumn(column)">
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
                numberOfColumns : 5,
                remainder : this.questions % 5,
                maxRows : Math.floor(((this.questions - 1) / 5)) + 1,
            }
        },
        methods: {
            rowsPerColumn : function (column) {
                if(column === 5) {
                    if (this.remainder === 1){
                        return this.maxRows - 4
                    } else if (this.remainder === 2){
                        return this.maxRows - 3
                    } else if (this.remainder === 3){
                        return this.maxRows - 2
                    } else if (this.remainder === 4){
                        return this.maxRows - 1
                    }else {
                        return this.maxRows
                    }
                } else {
                    return this.maxRows
                }
            },
        }
    }
</script>

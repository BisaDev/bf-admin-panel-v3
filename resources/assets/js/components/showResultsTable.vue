<template>
    <tr>
        <td>{{ this.question.question_number }}</td>
        <td>{{ this.correctAnswer.correct_1.toUpperCase() }}</td>
        <td :class="answerBackground">{{ this.question.answer ? this.question.answer.toUpperCase() : '' }}</td>
        <template v-if="this.question.answer ? this.question.answer.toUpperCase() === this.correctAnswer.correct_1.toUpperCase() || this.question.answer === this.correctAnswer.correct_2 || this.question.answer === this.correctAnswer.correct_3 || this.question.answer === this.correctAnswer.correct_4 || this.question.answer === this.correctAnswer.correct_5 : ''">
            <td v-if="questionUnderstood"></td>
            <td v-else>
                <input type="checkbox" @click="toggleActive"
                       :name="'understood_' + this.section + '_' + this.question.question_number">
            </td>
        </template>
        <template v-else>
            <td v-if="questionUnderstood">
                <span class="badge badge-pill badge-success"><i class="ti-check" @click="toggleActive"></i></span>
            </td>
            <td v-else>
                <input type="checkbox" @click="toggleActive"
                       :name="'understood_' + this.section + '_' + this.question.question_number">
            </td>
        </template>
        <td>{{ this.correctAnswer.topic }}</td>
        <td data-toggle="tooltip" title="Answer Explanation"><a href="#" data-toggle="modal" :data-target="'#answerExplanationModal_' + this.question.id"><i class="ti-info-alt"></i></a></td>
    </tr>
</template>

<script>
    export default {
        props: ['question', 'section', 'url', 'correct-answer', 'section-id', 'answer-background'],
        data() {
            return {
                questionUnderstood : this.question.understood,
            }
        },
        methods: {
            toggleActive() {
                axios.post(this.url, {
                    section: this.sectionId,
                    question: this.question.question_number,
                    understood: this.questionUnderstood,
                })
                    .then((response) => {
                        this.questionUnderstood = response.data;
                    });
            },
        },
    }
</script>
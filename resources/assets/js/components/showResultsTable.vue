<template>
    <tr>
        <td>{{ this.question.question_number }}</td>
        <td>{{ this.question.answer.toUpperCase() }}</td>
        <td>{{ this.correctAnswer.correct_1.toUpperCase() }}</td>
        <td>{{ this.question.guessed ? 'Guessed' : '-' }}</td>
        <template v-if="this.question.answer === this.correctAnswer.correct_1.toLowerCase() || this.question.answer === this.correctAnswer.correct_2 || this.question.answer === this.correctAnswer.correct_3 || this.question.answer === this.correctAnswer.correct_4 || this.question.answer === this.correctAnswer.correct_5">
            <td>
                <span class="badge badge-success">Correct</span>
            </td>
            <td v-if="questionUnderstood">
                <span class="badge badge-pill badge-success"><i class="ti-check" @click="toggleActive"></i></span>
            </td>
            <td v-else>
                <input type="checkbox" v-model="questionUnderstood" @click="toggleActive"
                       :name="'understood_' + this.section + '_' + this.question.question_number">
            </td>
        </template>
        <template v-else>
            <td><span class="badge badge-danger">Incorrect</span></td>
            <td v-if="questionUnderstood">
                <span class="badge badge-pill badge-success"><i class="ti-check" @click="toggleActive"></i></span>
            </td>
            <td v-else>
                <input type="checkbox" v-model="questionUnderstood" @click="toggleActive"
                       :name="'understood_' + this.section + '_' + this.question.question_number">
            </td>
        </template>
        <td><a href="#" data-toggle="modal" :data-target="'#answerExplanationModal_' + this.question.id"><i class="ti-info-alt"></i></a></td>
    </tr>
</template>

<script>
    export default {
        props: ['question', 'section', 'url', 'correct-answer', 'section-id'],
        data() {
            return {
                understood: 0,
                questionUnderstood : this.question.understood,
            }
        },
        methods: {
            toggleActive() {
                this.questionUnderstood = !this.questionUnderstood;
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
<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th width="300">Subject</th>
                        <th width="300" class="text-center">Tagged Questions</th>
                        <th width="300" class="text-center">Untagged</th>
                        <th width="200"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="subject in subjects">
                        <th>{{ subject.name }}</th>
                        <td class="text-center">{{ subject.tagged_questions_count }}</td>
                        <td class="text-center">{{ subject.untagged_questions_count }}</td>
                        <td class="text-center">
                            <a :href="`${tagging_route}/${subject.id}`">
                                <button  class="btn btn-info" :disabled="subject.untagged_questions_count === 0">
                                    Start Tagging
                                </button>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-box">
                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th width="400">Instructor</th>
                        <th width="200">Tagged Questions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="instructor in instructors">
                        <th>{{ instructor.name }} {{ instructor.last_name }}</th>
                        <td>{{ instructor.tag_logs.length }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "subjects": Array,
            "instructors": Array,
            "tagging_route": String
        }
    }
</script>

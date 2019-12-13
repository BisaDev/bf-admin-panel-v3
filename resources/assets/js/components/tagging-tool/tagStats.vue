<template>
    <div id="index-container" data-model="minigame" class="row">
        <div class="col-sm-12">
            <div class="card-box">

                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th width="300">Topic</th>
                        <th width="300">Tagged Questions</th>
                        <th width="300">Untagged</th>
                        <th width="300"></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>AP- Physics</th>
                            <td>500</td>
                            <td>146</td>
                            <td><button class="tt-button">Start Tagging</button></td>
                        </tr>
<!--                        Hard coded data , todo: map -->
                        <tr>
                            <th>SAT- Physics</th>
                            <td>500</td>
                            <td>146</td>
                            <td><button class="tt-button">Start Tagging</button></td>
                        </tr>
                        <tr>
                            <th>ACT- Physics</th>
                            <td>500</td>
                            <td>146</td>
                            <td><button class="tt-button">Start Tagging</button></td>
                        </tr>
                        <tr>
                            <th>SAT- Math</th>
                            <td>500</td>
                            <td>146</td>
                            <td><button class="tt-button">Start Tagging</button></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>

<style>
    .tt-button {
        color: white;
        padding: 5px;
        background-color: orange;
    }
</style>
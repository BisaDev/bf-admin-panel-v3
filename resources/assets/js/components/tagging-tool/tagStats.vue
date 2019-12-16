<template>
    <div id="index-container" class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th width="300">Topic</th>
                        <th width="300" class="text-center">Tagged Questions</th>
                        <th width="300" class="text-center">Untagged</th>
                        <th width="200"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>AP- Physics</th>
                        <td class="text-center">500</td>
                        <td class="text-center">146</td>
                        <td class="text-center"><button class="tt-button">Start Tagging</button></td>
                    </tr>
                    <!--                        Hard coded data , todo: map -->
                    <tr>
                        <th>SAT- Physics</th>
                        <td class="text-center">500</td>
                        <td class="text-center">146</td>
                        <td class="text-center"><button class="tt-button">Start Tagging</button></td>
                    </tr>
                    <tr>
                        <th>ACT- Physics</th>
                        <td class="text-center">500</td>
                        <td class="text-center">146</td>
                        <td class="text-center"><button class="tt-button">Start Tagging</button></td>
                    </tr>
                    <tr>
                        <th>SAT- Math</th>
                        <td class="text-center">500</td>
                        <td class="text-center">146</td>
                        <td class="text-center"><button class="tt-button">Start Tagging</button></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card-box">
                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th width="300">Instructor</th>
                        <th width="600">Tagged Questions by Instructor</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>David Smith</th>
                        <td>500</td>
                    </tr>
                    <!--                        Hard coded data , todo: map -->

                    <tr>
                        <th>Rachel Perez</th>
                        <td>345</td>
                    </tr>
                    <tr>
                        <th>Jose Ramirez</th>
                        <td>94</td>
                    </tr>
                    <tr>
                        <th>Jessie Johnson</th>
                        <td>4</td>
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
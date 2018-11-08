<template>
    <div>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Choose a CSV File..."
                   :value="file.name" @click="launchFilePicker" readonly>
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" @click="launchFilePicker">
                    <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
                </button>
            </span>
        </div>

        <input class="input-file" type="file" name="csv" ref="file" v-uploader>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                file: '',
            }
        },
        methods: {
            launchFilePicker() {
                this.$refs.file.click();
            }
        },
        directives: {
            uploader: {
                bind(el, binding, vnode) {
                    el.addEventListener('change', e => {
                        vnode.context.file = e.target.files[0];
                    });
                }
            },
        },
    }
</script>
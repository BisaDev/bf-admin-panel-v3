export default {
    init () {
        const domElement = 'upload-file'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#upload-file',
            data: {
                file: '',
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
        });
    },
}

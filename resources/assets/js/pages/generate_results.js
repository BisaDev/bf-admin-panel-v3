export default {
    init () {
        const domElement = 'generate-results'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#generate-results',
            data: {
                exam_id: '',
                section_id: '',
                examSections: [],
                checkedSections: [],
                exam_sections_url: '',
            },
            methods: {
                loadSections() {
                    if(this.exam_id != '' && this.section_id != ''){
                        this.examSections = [];
                        let examSections = this.examSections;

                        axios.post(this.exam_sections_url, {
                            exam_id: this.exam_id,
                            section_id: this.section_id,
                        }).then(function (response) {
                            $.each(response.data, function(i, item){
                                examSections.push(item);
                            });
                        });
                    }
                },
                toggleActive(id) {
                    if (this.checkedSections.includes(id)) {
                        var index = this.checkedSections.indexOf(id);
                        this.checkedSections.splice(index, 1);
                    } else {
                        this.checkedSections.push(id);
                    }
                }
            },
            mounted() {
                if(this.$el.attributes['data-exam-sections-url'] !== undefined) {
                    this.exam_sections_url = this.$el.attributes['data-exam-sections-url'].value;
                }
            }
        });
    },
}

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
                sections: [],
                section_id: '',
                sections_url: '',
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
                loadExamSections() {
                    this.sections =[];
                    this.section_id = '';
                    let sections = this.sections;

                    axios.post(this.sections_url, {
                        exam_id: this.exam_id,
                    }).then(function (response) {
                        $.each(response.data, function(i, item){
                            sections.push(item);
                        });
                    });
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
                if(this.$el.attributes['data-sections-url'] !== undefined) {
                    this.sections_url = this.$el.attributes['data-sections-url'].value;
                }
            }
        });
    },
}

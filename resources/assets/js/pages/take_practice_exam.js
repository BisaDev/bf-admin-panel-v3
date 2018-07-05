
export default {
    init () {
        const domElement = 'take-practice-exam'
        if(document.getElementById(domElement)) {
            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#take-practice-exam',
            data: {
                selected: [],
                sections: [
                    {"id": "1", "name": "Reading Comprehension"},
                    {"id": "2", "name": "Writing and Language"},
                    {"id": "3", "name": "Math No Calculator"},
                    {"id": "4", "name": "Math With Calculator"},
                ],
                examSection: false,
            },
            computed: {
                selectAll: {
                    get: function () {
                        return this.sections ? this.selected.length == this.sections.length : false;
                    },
                    set: function (value) {
                        var selected = [];

                        if (value) {
                            this.sections.forEach(function (section) {
                                selected.push(section.id);
                            });
                        }

                        this.selected = selected;
                    }
                }
            }
        });
    },
}

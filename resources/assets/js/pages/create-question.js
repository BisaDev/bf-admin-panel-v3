import getAcademicContent from './mixins/getAcademicContent';
import imagePreview from './mixins/imagePreview';
import tagRepository from './mixins/tagRepository';
import cropImages from './mixins/cropImages';
import {
    fabric
} from 'fabric'

export default {
    init() {
        const domElement = 'create-question';
        if (document.getElementById(domElement)) {

            require('typeahead.js');
            require('bootstrap-tagsinput');

            this.execute()
        }
    },
    execute() {
        new Vue({
            el: '#create-question',
            data: {
                children: [],
                answer_groups: [],
                type: '',
                photo: '',
                other_photo: '',
                canvas_bg: '',
                number_of_answers_allowed: 4,
                allows_answers: false,
                type_has_canvas: false,
                type_shows_answers: true,
                type_answer_has_additional_data: false,
            },
            mixins: [getAcademicContent, imagePreview, tagRepository, cropImages],
            beforeMount: function() {

                //Look for question type and assign the selected to Vue data value
                let type_select = $('#type');
                if (type_select.length > 0) {
                    this.type = type_select.children('option:selected').val();
                    this.assignValuesOnType(this.type);
                }
            },
            watch: {
                photo: function(val) {


                    console.log(val);
                    console.log(this.type_has_canvas);

                    if (this.type_has_canvas) {
                        this.canvas_bg = val;
                    }
                },
                canvas_bg: function(val) {

                    let image = new Image();
                    let vue_instance = this;

                    image.onload = function() {
                        vue_instance.canvas.setBackgroundImage(val, vue_instance.canvas.renderAll.bind(vue_instance.canvas));
                        vue_instance.canvas.setDimensions({
                            width: image.width,
                            height: image.height
                        });
                    };

                    image.src = val;
                },
                type: function(val) {
                    this.assignValuesOnType(val);
                },
            },
            methods: {
                /**
                 * Handle the data when the crop process is finished.
                 *
                 * @param {String} imageData
                 */
                handleCrop(imageData, index = null) {

                    if (index === null) {
                        this.photo = imageData;
                    } else if (index === 5) {
                        this.other_photo = imageData;
                    } else {
                        this.children[index].photo = imageData;
                    }
                },

                setDefaultQuestions(event) {
                    if (this.type == 0 || this.type == 7) {
                        for (let c = 0; c < 4; c++) {
                            this.addChildren();
                        }
                    } else {
                        this.children = [];
                    }
                },
                addChildren(event, obj, group) {
                    if (this.children.length < this.number_of_answers_allowed) {

                        let obj_data, obj_id;
                        if (obj !== undefined && obj !== null) {
                            obj_data = this.prepareObjectData(obj);
                            obj_id = obj.obj_id;
                        } else {
                            obj_data = '';
                            obj_id = '';
                        }

                        this.children.push({
                            name: (this.type == 5) ? 'Touch select' : '',
                            photo: '',
                            is_correct: false,
                            remove_photo: false,
                            obj_id: obj_id,
                            obj_data: obj_data,
                            answer_group: group
                        });
                    }
                },
                removeChildren(index) {
                    //Get object_id and object_data to copy to another answer OR for removal in canvas later
                    let obj_id = this.children[index].obj_id;
                    let obj_data = this.children[index].obj_data;
                    //Get answer_group for removal in case it's the last answer of the group
                    let answer_group = this.children[index].answer_group;
                    //Remove answer from answers array
                    this.children.splice(index, 1);

                    //Find another answer in the same group
                    let group_member = _.find(this.children, function(g) {
                        return g.answer_group == answer_group;
                    });
                    if (!group_member) {
                        //If no other answer in group, remove group
                        let group_index = _.findIndex(this.answer_groups, function(g) {
                            return g.group_number == answer_group;
                        });
                        this.answer_groups.splice(group_index, 1);

                        let answer_list = this.children;
                        _.each(this.answer_groups, function(answer_group, index) {
                            if (index >= group_index) {
                                //Look for answers in a group above the one we deleted
                                let answers = _.filter(answer_list, function(a) {
                                    //Return answers 2 numbers above, if we removed Group 1, the index was 0,
                                    //so we need answers on Group 2 to move them to Group 1
                                    return a.answer_group == index + 2;
                                });

                                _.each(answers, function(answer) {
                                    answer.answer_group = index + 1;
                                });
                            }
                        });

                        //Remove object from canvas only if there are no more answers in group
                        if (this.type_has_canvas) {
                            let canvas_objects = this.canvas.getObjects();

                            let square = _.find(canvas_objects, function(o) {
                                return o.obj_id == obj_id;
                            });

                            if (square) {
                                this.canvas.remove(square);
                            }
                        }
                    } else if (group_member && obj_id != "") {
                        //If there's another answer in group and we're removing the answer with obj_id and obj_data, move data to
                        //other answer in group
                        group_member.obj_id = obj_id;
                        group_member.obj_data = obj_data;
                    }
                },
                saveQuestionAndAddMore(event) {
                    $(event.target).siblings('[name="add_more"]').val('true');
                    this.$el.children[0].submit();
                },
                createObject(left, top, width, height, obj_id, id_offset) {
                    let square = new fabric.Rect({
                        width: width,
                        height: height,
                        left: left,
                        top: top,
                        stroke: '#000',
                        fill: 'transparent',
                    });

                    let tag_text = 'Answer ' + (this.children.length + id_offset);

                    if (this.type == 4) {
                        tag_text = 'Group ' + (this.answer_groups.length + id_offset);
                    }

                    let tag = new fabric.Text(tag_text, {
                        fontSize: 12,
                        left: left + 5,
                        top: top + 5
                    });

                    return new fabric.Group([square, tag], {
                        obj_id: obj_id
                    });
                },
                prepareObjectData(obj) {
                    return JSON.stringify({
                        top: parseFloat(obj.top.toFixed(2)),
                        left: parseFloat(obj.left.toFixed(2)),
                        width: obj.width * obj.scaleX - 1,
                        height: obj.height * obj.scaleY - 1
                    });
                },
                assignValuesOnType(type) {
                    this.number_of_answers_allowed = 4;
                    this.allows_answers = true;
                    this.type_has_canvas = false;
                    this.type_shows_answers = true;
                    this.type_answer_has_additional_data = false;

                    switch (type) {
                        case '0': //Mutiple choice
                            this.type_answer_has_additional_data = true;
                            break;
                        case '3': //Apple pencil
                        case '6': //Research and Report back
                            this.allows_answers = false;
                            break;
                        case '4': //Drag and drop
                            this.type_has_canvas = true;
                            this.type_answer_has_additional_data = true;
                            break;
                        case '5': //Touch select
                            this.number_of_answers_allowed = 1;
                            this.type_has_canvas = true;
                            this.type_shows_answers = false;
                            break;
                        case '7': //Long Passage
                            this.type_answer_has_additional_data = true;
                            break;
                    }
                }
            },
            mounted() {

                if ($('#dnd-canvas').length > 0) {
                    let canvas = new fabric.Canvas('dnd-canvas', {
                        width: '0',
                        height: '0',
                        renderOnAddRemove: true
                    });

                    this.canvas = canvas;
                    let started, x, y;
                    let vue_instance = this;

                    canvas.on('object:added', function(event) {
                        let obj = event.target;

                        if (!_.find(vue_instance.children, function(o) {
                                return o.obj_id == obj.obj_id;
                            })) {
                            let group = 0;
                            if (vue_instance.type == 4) { //Drag and Drop
                                group = vue_instance.answer_groups.length + 1;

                                vue_instance.answer_groups.push({
                                    group_number: group
                                });
                            }
                            vue_instance.addChildren(null, obj, group);
                        }
                    });

                    canvas.on('object:removed', function(event) {

                        let canvas_objects = canvas.getObjects();

                        $.each(vue_instance.children, function(index, answer) {

                            let square = _.find(canvas_objects, function(o) {
                                return o.obj_id == answer.obj_id;
                            });

                            //If answer is second in group, it doesn't have canvas object information
                            if (square) {
                                let tag_text = 'Answer ' + (index + 1);

                                if (vue_instance.type == 4) { //Drag and drop
                                    tag_text = 'Group ' + (index + 1);
                                }

                                square.getObjects()[1].setText(tag_text);
                                canvas.renderAll();
                            }
                        });
                    });

                    canvas.on('object:modified', function(event) {
                        let obj = event.target;

                        let answer = _.find(vue_instance.children, function(o) {
                            return o.obj_id == obj.obj_id;
                        });

                        answer.obj_data = vue_instance.prepareObjectData(obj);
                    });

                    canvas.on('mouse:down', function(options) {
                        if (canvas.getActiveObject()) {
                            return;
                        }

                        if (vue_instance.children.length < vue_instance.number_of_answers_allowed) {
                            started = true;
                            let pointer = canvas.getPointer(options.e);
                            x = pointer.x;
                            y = pointer.y;

                            let group = vue_instance.createObject(x, y, 129, 129, _.uniqueId('rect'), 1);

                            canvas.add(group);
                            canvas.setActiveObject(group);
                        }
                    });
                }

                if (this.$el.attributes['data-answers'] !== undefined) {
                    let answers = $.parseJSON(this.$el.attributes['data-answers'].value);
                    let vue_instance = this;

                    $.each(answers, function(index, answer) {

                        vue_instance.children.push({
                            name: answer.text,
                            current_photo: answer.photo,
                            photo: '',
                            is_correct: (answer.is_correct == 1 || answer.is_correct == 'on'),
                            remove_photo: false,
                            obj_id: 'rect' + index,
                            obj_data: JSON.stringify(answer.object_data),
                            answer_group: answer.group,
                            id: answer.id,
                        });

                        if (vue_instance.type_has_canvas) {

                            if (vue_instance.type == 4 && answer.group != null) { //Drag and drop
                                let group_exists = _.find(vue_instance.answer_groups, function(g) {
                                    return g.group_number == answer.group;
                                });

                                if (!group_exists) {
                                    vue_instance.answer_groups.push({
                                        group_number: answer.group
                                    });
                                }
                            }

                            let object_data = answer.object_data;

                            if (object_data !== null) {
                                let group = vue_instance.createObject(object_data.left, object_data.top, object_data.width, object_data.height, 'rect' + index, 0);

                                vue_instance.canvas.add(group);
                            }
                        }
                    });

                    if (vue_instance.type_has_canvas) {
                        vue_instance.canvas_bg = $('#question_photo').attr('src');
                    }
                }
            },
        });
    },
}
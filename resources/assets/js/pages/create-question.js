import getAcademicContent from './mixins/getAcademicContent';
import imagePreview from './mixins/imagePreview';
import tagRepository from './mixins/tagRepository';
import {fabric} from 'fabric'

export default {
    init () {
        const domElement = 'create-question'
        if(document.getElementById(domElement)) {

            require('typeahead.js');
            require('bootstrap-tagsinput');

            this.execute()
        }
    },
    execute () {
        new Vue({
            el: '#create-question',
            data: {
                children: [],
                type: '',
                photo: '',
                number_of_answers_allowed: 4,
                allows_answers: false,
                type_has_canvas: false,
                type_shows_answers: true,
                type_answer_has_additional_data: false,
            },
            mixins: [getAcademicContent, imagePreview, tagRepository],
            beforeMount: function () {

                //Look for question type and assign the selected to Vue data value
                if($('#type').length > 0){
                    this.type = $('#type').children('option:selected').val();
                    this.assignValuesOnType(this.type);
                }
            },
            watch: {
                photo: function(val){

                    let image = new Image();
                    let vue_instance = this;

                    image.onload = function () {
                        if(vue_instance.type_has_canvas) {
                            vue_instance.canvas.setBackgroundImage(val, vue_instance.canvas.renderAll.bind(vue_instance.canvas))
                            vue_instance.canvas.setDimensions({width: image.width, height: image.height});
                        }
                    }
                    image.src = val;
                },
                type: function (val) {
                    this.assignValuesOnType(val);
                }
            },
            methods: {
                addChildren(event, obj){
                    if(this.children.length < this.number_of_answers_allowed){

                        let obj_data, obj_id;
                        if(obj !== undefined){
                            obj_data = this.prepareObjectData(obj);
                            obj_id = obj.obj_id;
                        }else{
                            obj_data = '';
                            obj_id = '';
                        }

                        this.children.push({name: (this.type == 5)? 'Touch select' : '', photo: '', is_correct: false, remove_photo: false, obj_id: obj_id, obj_data: obj_data});
                    }
                },
                removeChildren(index){
                    let obj_id = this.children[index].obj_id;
                    this.children.splice(index, 1);

                    if(this.type_has_canvas){
                        let canvas_objects = this.canvas.getObjects();

                        let square = _.find(canvas_objects, function(o) { return o.obj_id == obj_id; });

                        if(square){
                            this.canvas.remove(square);
                        }
                    }
                },
                saveQuestionAndAddMore(event){
                    $(event.target).siblings('[name="add_more"]').val('true');
                    this.$el.children[0].submit();
                },
                createObject(left, top, width, height, obj_id, id_offset){
                    let square = new fabric.Rect({
                        width: width,
                        height: height,
                        left: left,
                        top: top,
                        stroke: '#000',
                        fill: 'transparent',
                    });

                    let tag = new fabric.Text('Answer '+(this.children.length+id_offset), {
                        fontSize: 12,
                        left: left+5,
                        top: top+5
                    });

                    let group = new fabric.Group([square, tag], {
                        obj_id: obj_id
                    });

                    return group;
                },
                prepareObjectData(obj){
                    return JSON.stringify({top: parseFloat(obj.top.toFixed(2)), left: parseFloat(obj.left.toFixed(2)), width: obj.width*obj.scaleX-1, height: obj.height*obj.scaleY-1});
                },
                assignValuesOnType(type){
                    this.number_of_answers_allowed = 4;
                    this.allows_answers = true;
                    this.type_has_canvas = false;
                    this.type_shows_answers = true;
                    this.type_answer_has_additional_data = false;

                    switch(type){
                        case '0': //Mutiple choice
                            this.type_answer_has_additional_data = true;
                            break;
                        case '3': //Apple pencil
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
                    }
                },
            },
            mounted() {

                if($('#dnd-canvas').length > 0){
                    let canvas = new fabric.Canvas('dnd-canvas', {
                        width: '0',
                        height: '0',
                        renderOnAddRemove: true
                    });

                    this.canvas = canvas;
                    let started, x, y;
                    let vue_instance = this;

                    canvas.on('object:added', function(event){
                        let obj = event.target;

                        if(!_.find(vue_instance.children, function(o) { return o.obj_id == obj.obj_id; })){
                            vue_instance.addChildren(null, obj);
                        }
                    });

                    canvas.on('object:removed', function(event){

                        let canvas_objects = canvas.getObjects();

                        $.each(vue_instance.children, function(index, answer){

                            let square = _.find(canvas_objects, function(o) { return o.obj_id == answer.obj_id; });

                            square.getObjects()[1].setText('Answer '+(index+1));
                            canvas.renderAll();
                        });
                    });

                    canvas.on('object:modified', function(event){
                        let obj = event.target;

                        let answer = _.find(vue_instance.children, function(o) { return o.obj_id == obj.obj_id; });

                        answer.obj_data = vue_instance.prepareObjectData(obj);
                    });

                    canvas.on('mouse:down', function(options) {
                        if(canvas.getActiveObject()){
                            return;
                        }

                        if(vue_instance.children.length < vue_instance.number_of_answers_allowed) {
                            started = true;
                            var pointer = canvas.getPointer(options.e);
                            x = pointer.x;
                            y = pointer.y;

                            let group = vue_instance.createObject(x, y, 99, 99, _.uniqueId('rect'), 1);

                            canvas.add(group);
                            canvas.setActiveObject(group);
                        }
                    });
                }

                if(this.$el.attributes['data-answers'] !== undefined) {
                    let answers = $.parseJSON(this.$el.attributes['data-answers'].value);
                    let vue_instance = this;

                    $.each(answers, function(index, answer){
                        
                        vue_instance.children.push({
                            name: answer.text,
                            photo: answer.photo,
                            is_correct: (answer.is_correct == 1 || answer.is_correct == 'on')? true : false,
                            remove_photo: false,
                            obj_id: 'rect'+index,
                            obj_data: JSON.stringify(answer.object_data),
                            id: answer.id
                        });

                        if(vue_instance.type_has_canvas){

                            let object_data = answer.object_data;

                            let group = vue_instance.createObject(object_data.left, object_data.top, object_data.width, object_data.height, 'rect'+index, 0);

                            vue_instance.canvas.add(group);
                        }
                    });

                    if(vue_instance.type_has_canvas){
                        vue_instance.photo = $('#question_photo').attr('src');
                    }
                }
            }
        });
    },
}

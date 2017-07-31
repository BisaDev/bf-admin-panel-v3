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
            },
            mixins: [getAcademicContent, imagePreview, tagRepository],
            beforeMount: function () {

                //Look for question type and assign the selected to Vue data value
                if($('#type').length > 0){
                    this.type = $('#type').children('option:selected').val();
                }
            },
            watch: {
                photo: function(val){

                    let image = new Image();
                    let vue_instance = this;

                    image.onload = function () {
                        if(vue_instance.type == 4) { //Drag and drop
                            vue_instance.canvas.setBackgroundImage(val, vue_instance.canvas.renderAll.bind(vue_instance.canvas))
                            vue_instance.canvas.setDimensions({width: image.width, height: image.height});
                        }
                    }
                    image.src = val;
                }
            },
            methods: {
                addChildren(event, obj){
                    if(this.children.length < 4){

                        let obj_data, obj_id;
                        if(obj !== undefined){
                            obj_data = this.prepareObjectData(obj);
                            obj_id = obj.obj_id;
                        }else{
                            obj_data = '';
                            obj_id = '';
                        }

                        this.children.push({name: '', photo: '', is_correct: false, remove_photo: false, obj_id: obj_id, obj_data: obj_data});
                    }
                },
                removeChildren(index){
                    let obj_id = this.children[index].obj_id;
                    this.children.splice(index, 1);

                    if(this.type == 4){
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
                questionTypeAllowsAnswers(){
                    let allows = true;

                    switch(this.type){
                        case "3": //Apple pencil
                            allows = false;
                    }

                    return allows;
                },
                questionAnswersHaveAdditionalData(){
                    let hasData = false;

                    switch(this.type){
                        case '0': //Multiple choice
                        case '4': //Drag and drop
                            hasData = true;
                            break;
                    }

                    return hasData;
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
                    return JSON.stringify({top: obj.top.toFixed(2), left: obj.left.toFixed(2), width: obj.width*obj.scaleX-1, height: obj.height*obj.scaleY-1});
                }
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

                        if(vue_instance.children.length < 4) {
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
                            obj_data: answer.object_data,
                            id: answer.id
                        });

                        if(vue_instance.type == '4'){

                            let object_data = answer.object_data;

                            let group = vue_instance.createObject(object_data.left, object_data.top, object_data.width, object_data.height, 'rect'+index, 0);

                            vue_instance.canvas.add(group);
                        }
                    });

                    if(vue_instance.type == '4'){
                        vue_instance.photo = $('#question_photo').attr('src');
                    }
                }
            }
        });
    },
}

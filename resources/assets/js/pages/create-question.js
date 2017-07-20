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
                photo: function (val) {
                    let image = new Image();
                    let vue_instance = this;

                    image.onload = function(){

                        vue_instance.canvas.setBackgroundImage(val, vue_instance.canvas.renderAll.bind(vue_instance.canvas))
                        vue_instance.canvas.setDimensions({width: image.width, height: image.height});
                    }
                    image.src = val;
                }
            },
            methods: {
                addChildren(event, obj_id){
                    if(this.children.length < 4){
                        this.children.push({name: '', photo: '', is_correct: false, remove_photo: false, obj_id: obj_id});
                    }
                },
                removeChildren(index){
                    let obj_id = this.children[index].obj_id;
                    this.children.splice(index, 1);

                    if(this.type == 4){
                        let canvas_objects = this.canvas.getObjects();

                        let square = _.find(canvas_objects, function(o) { return o.obj_id == obj_id; });

                        if(square){
                            square.remove();
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
                        case "3":
                            allows = false;
                    }

                    return allows;
                }
            },
            mounted() {

                if(this.$el.attributes['data-answers'] !== undefined) {
                    let answers = $.parseJSON(this.$el.attributes['data-answers'].value);
                    let vue_instance = this;
                    
                    $.each(answers, function(index, answer){
                        vue_instance.children.push({name: answer.text, photo: answer.photo, is_correct: (answer.is_correct == 1 || answer.is_correct == 'on')? true : false, remove_photo: false, id: answer.id});
                    });
                }

                if($('#dnd-canvas').length > 0){
                    let canvas = new fabric.Canvas('dnd-canvas', {
                        width: '0',
                        height: '0',
                        renderOnAddRemove: true
                    });

                    this.canvas = canvas;
                    let started, x, y;
                    let vue_instance = this;

                    canvas.on('object:added', function(obj){
                        let obj_id = obj.target.obj_id;

                        if(!_.find(vue_instance.children, function(o) { return o.obj_id == obj_id; })){
                            vue_instance.addChildren(obj_id);
                        }
                    });

                    canvas.on('object:removed', function(obj){
                        let obj_id = obj.target.obj_id;

                        let answer_index = _.findIndex(vue_instance.children, function(o) { return o.obj_id == obj_id; });

                        if(answer_index != -1){
                            vue_instance.removeChildren(answer_index);
                        }
                    });

                    canvas.on('object:modified', function(obj){
                        console.log('Top: '+obj.target.top+' Left:'+obj.target.left+' Width:'+obj.target.width+' Height:'+obj.target.height);
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

                            var square = new fabric.Rect({
                                width: 100,
                                height: 100,
                                left: x,
                                top: y,
                                stroke: '#000',
                                fill: 'transparent',
                            });

                            var tag = new fabric.Text('Answer '+(vue_instance.children.length+1), {
                                fontSize: 12,
                                left: x+5,
                                top: y+5
                            });

                            var group = new fabric.Group([square, tag], {
                                obj_id: _.uniqueId('rect')
                            });

                            canvas.add(group);
                            canvas.setActiveObject(group);
                        }
                    });

                    /*canvas.on('mouse:move', function(options) {
                        if(!started) {
                            return false;
                        }

                        var pointer = canvas.getPointer(options.e);
                        var w = Math.abs(pointer.x - x),
                            h = Math.abs(pointer.y - y);

                        if (!w || !h) {
                            return false;
                        }

                        var square = canvas.getActiveObject();
                        square.set('width', w).set('height', h);
                    });

                    canvas.on('mouse:up', function(options) {
                        var square = canvas.getActiveObject();

                        if(started && (square.width < 50 || square.height < 50)){
                            canvas.remove(square);
                        }

                        if(started) {
                            started = false;

                        }
                    });*/
                }
            }
        });
    },
}

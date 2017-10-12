<div id="add-children" v-if="type == 4">
    @if($errors->has('answers') || $errors->has('answers.*'))
        <div class="alert alert-danger" role="alert">
            <ul>
                @if($errors->has('answers'))<li>{{ $errors->first('answers') }}</li>@endif
                @foreach($errors->get('answers.*') as $answer_errors)
                    @foreach($answer_errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif
    <div v-for="(group, index) in answer_groups">
        <div class="form-group col-md-12">
            <h2>Group @{{ index+1 }}</h2>
            <button type="button" class="btn btn-sm btn-default" @click="addChildren($event, null, index+1)" v-show="children.length < 4" >Add answer to group<span class="m-l-5"><i class="fa fa-plus"></i></span></button>
        </div>
    
        <div class="col-md-3 col-sm-6" v-for="(answer, index2) in children" v-if="answer.answer_group == index+1" v-show="type_shows_answers">
            <div class="panel panel-border panel-info">
                <div class="panel-heading input-clear active">
                    <h3 class="panel-title">Answer @{{ index2+1 }}</h3>
                    <span class="panel-title" @click="removeChildren(index2)">&times;</span>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <input type="text" class="form-control" v-bind:name="'answers['+index2+'][text]'" placeholder="Answer / Word" v-model="answer.name" maxlength="48">
                        
                            <input type="hidden" v-bind:name="'answers['+index2+'][id]'" v-model="answer.id">
                        </div>
                    </div>
                    <div class="row" v-show="type_answer_has_additional_data">
                        <div class="form-group col-xs-12 m-b-0">
                            <div class="droppable droppable-small">
                                <span v-if="!answer.photo">Drag an image or click to browse</span>
                                <img v-else :src="answer.photo" />
                                <input v-bind:name="'answers['+index2+'][photo]'" type="file" @change="onFileChange($event, index2)">
                            </div>
                            <div class="checkbox checkbox-info m-b-0" v-if="answer.photo">
                                <input v-bind:id="'remove_photo_'+index2" type="checkbox" v-model="answer.remove_photo" v-bind:name="'answers['+index2+'][remove_photo]'">
                                <label v-bind:for="'remove_photo_'+index2">Remove photo</label>
                            </div>
                        </div>
                        <input type="hidden" v-bind:name="'answers['+index2+'][obj_data]'" v-model="answer.obj_data">
                        <input type="hidden" v-bind:name="'answers['+index2+'][group]'" v-model="answer.answer_group">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
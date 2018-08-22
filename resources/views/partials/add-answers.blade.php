<div id="add-children" v-if="type != 4">
    <div class="form-group col-md-12">
        <button type="button" class="btn btn-sm btn-default" @click="addChildren" v-if="!type_has_canvas" >Add answer<span class="m-l-5"><i class="fa fa-plus"></i></span></button>
        <p class="text-muted m-t-5">{{ $help_text or '' }}</p>
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
    </div>

    <div class="col-sm-6" v-for="(answer, index) in children" v-show="type_shows_answers">
        <div class="panel panel-border panel-info">
            <div class="panel-heading input-clear active">
                <h3 class="panel-title">Answer @{{ index+1 }}</h3>
                <span class="panel-title" @click="removeChildren(index)">&times;</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-xs-12">
                        <template v-if="answer.photo">
                            <input type="text" class="form-control" v-bind:name="'answers['+index+'][text]'" placeholder="Answer / Word" v-model="answer.name" maxlength="50">
                        </template>
                        <template v-else>
                            <textarea class="form-control" cols="4" v-bind:name="'answers['+index+'][text]'" placeholder="Answer / Word" v-model="answer.name" maxlength="180"></textarea>
                        </template>

                        <input type="hidden" v-bind:name="'answers['+index+'][id]'" v-model="answer.id">
                    </div>
                </div>
                <div class="row" v-show="type_answer_has_additional_data">
                    <div class="form-group col-md-4" v-show="type == 0 || type == 7">
                        <div class="checkbox checkbox-primary">
                            <input v-bind:id="'is_correct_'+index" type="checkbox" v-model="answer.is_correct" v-bind:name="'answers['+index+'][is_correct]'">
                            <label v-bind:for="'is_correct_'+index">Correct</label>
                        </div>
                    </div>
                    <div class="form-group col-md-8 m-b-0">
                        <div class="droppable droppable-small">
                            <span v-if="!answer.photo">Drag an image or click to browse</span>
                            <img v-else :src="answer.photo" />
                            <input v-bind:name="'answers['+index+'][photo]'" type="file" @change="openCropImage($event, handleCrop, null, index)">
                            <input v-bind:name="'answers['+index+'][photo_cropped]'" type="hidden" :value="answer.photo" >
                        </div>
                    </div>
                    <input type="hidden" v-bind:name="'answers['+index+'][obj_data]'" v-model="answer.obj_data">
                </div>
                <div class="row" v-show="type_answer_has_additional_data" v-if="answer.current_photo">
                    <div class="form-group col-md-4 m-b-0">
                        <img class="img-responsive" :src="answer.current_photo" />
                    </div>
                    <div class="form-group col-md-8 m-b-0">
                        <div class="checkbox checkbox-info m-b-0">
                            <input v-bind:id="'remove_photo_'+index" type="checkbox" v-model="answer.remove_photo" v-bind:name="'answers['+index+'][remove_photo]'">
                            <label v-bind:for="'remove_photo_'+index">Remove photo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
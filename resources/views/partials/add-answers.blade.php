<div id="add-children">
    <div class="form-group col-md-12">
        <button type="button" class="btn btn-sm btn-default" @click="addChildren" >Add answer<span class="m-l-5"><i class="fa fa-plus"></i></span></button>
        <p class="text-muted m-t-5">{{ $help_text or '' }}</p>
    </div>

    <div class="col-sm-6" v-for="(answer, index) in children">
        <div class="panel panel-border panel-info">
            <div class="panel-heading input-clear active">
                <h3 class="panel-title">Answer @{{ index+1 }}</h3>
                <span class="panel-title" @click="removeChildren(index)">&times;</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-xs-12 {{ $errors->has('answers.*.text')? 'has-error' : '' }}">
                        <input type="text" class="form-control" v-bind:name="'answers['+index+'][text]'" placeholder="Answer / Word" v-model="answer.name">
                        @if($errors->has('answers.*.text'))
                            <span class="help-block">
                                <strong>{{ $errors->first('answers.*.text') }}</strong>
                            </span>
                        @endif
                        <input type="hidden" v-bind:name="'answers['+index+'][id]'" v-model="answer.id">
                    </div>
                </div>
                <div class="row" v-show="type == 0">
                    <div class="form-group col-md-4">
                        <div class="checkbox checkbox-primary">
                            <input v-bind:id="'is_correct_'+index" type="checkbox" v-model="answer.is_correct" v-bind:name="'answers['+index+'][is_correct]'" {{ old('is_correct') ? 'checked' : '' }}>
                            <label v-bind:for="'is_correct_'+index">Correct</label>
                        </div>
                    </div>
                    <div class="form-group col-md-8">
                        <div class="droppable droppable-small">
                            <span v-if="!answer.photo">Drag an image or click to browse</span>
                            <img v-else :src="answer.photo" />
                            <input v-bind:name="'answers['+index+'][photo]'" type="file" @change="onFileChange($event, index)">
                        </div>
                        @if($errors->has('photo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('photo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
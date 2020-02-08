@php $help_text = isset($help_text)? $help_text : '' @endphp
<div id="add-children">
    <div class="form-group col-md-12">
        <button type="button" class="btn btn-sm btn-default" @click="addChildren" >Add note<span class="m-l-5"><i class="fa fa-plus"></i></span></button>
        <p class="text-muted m-t-5">{{ $help_text  }}</p>
    </div>

    <div class="col-md-6" v-for="(note, index) in children">
        <div class="panel panel-border panel-info">
            <div class="panel-heading input-clear active">
                <h3 class="panel-title">Note</h3>
                <span class="panel-title" @click="removeChildren(index)">&times;</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" class="form-control" v-bind:name="'notes['+index+'][title]'" placeholder="Note title" v-model="note.name">
                    <input type="hidden" v-bind:name="'notes['+index+'][id]'" v-model="note.id">
                </div>
                <div class="form-group">
                    <textarea rows="11" v-bind:name="'notes['+index+'][text]'" class="form-control" v-model="note.text"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

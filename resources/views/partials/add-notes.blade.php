<div id="add-children">
    <div class="form-group col-md-12">
        <button type="button" class="btn btn-sm btn-default" @click="addChildren" >Add note<span class="m-l-5"><i class="fa fa-plus"></i></span></button>
        <p class="text-muted m-t-5">{{ $help_text or '' }}</p>
    </div>

    <div class="col-md-6" v-for="(note, index) in children">
        <div class="form-group">
            <span class="form-control input-clear active">
                <input type="text" v-bind:name="'notes['+index+'][title]'" placeholder="Note title" v-model="note.name">
                <span @click="removeChildren(index)">&times;</span>
            </span>
        </div>
        <div class="form-group">
            <textarea v-bind:name="'notes['+index+'][text]'" class="form-control"></textarea>
        </div>
    </div>
</div>
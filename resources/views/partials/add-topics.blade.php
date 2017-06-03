<div id="add-topics">
    <div class="form-group col-md-12">
        <button type="button" class="btn btn-sm btn-default" @click="addChildren" >Add topic<span class="m-l-5"><i class="fa fa-plus"></i></span></button>
    </div>

    <div class="form-group col-md-12" v-for="(topic, index) in children">
        <span class="form-control input-clear active">
            <input type="text" name="topics[]" placeholder="Topic name" v-model="topic.name">
            <span @click="removeChildren(index)">&times;</span>
        </span>
    </div>
</div>
<div id="add-children">
    <div class="form-group col-md-12">
        <button type="button" class="btn btn-sm btn-default" @click="addChildren" >Add room<span class="m-l-5"><i class="fa fa-plus"></i></span></button>
    </div>

    <div class="form-group col-md-12" v-for="(room, index) in children">
        <span class="form-control input-clear active">
            <input type="text" name="rooms[]" placeholder="Room name" v-model="room.name">
            <span @click="removeChildren(index)">&times;</span>
        </span>
    </div>
</div>
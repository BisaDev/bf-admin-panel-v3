<div id="add-topics">
    <div class="form-group col-md-12">
        <button type="button" class="btn btn-sm btn-default" @click="addTopic" >Add topic +</button>
    </div>

    <div class="form-group col-md-12" v-for="(topic, index) in topics">
        <span class="form-control input-clear active">
            <input type="text" name="topics[]" placeholder="Topic name" v-model="topic.name">
            <span @click="removeTopic(index)">&times;</span>
        </span>
    </div>
</div>
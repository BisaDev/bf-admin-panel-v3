<div class="row">
    <div class="form-group col-xs-12 {{ $errors->has('tags')? 'has-error' : '' }}">
        <label class="control-label" for="tags">Tags:</label>
        <select multiple id="tags" name="tags[]" data-role="tagsinput" class="form-control" data-tag_repository="{{ route('tags.repository') }}">
        @if(isset($tags))
            @foreach($tags as $tag)
            <option value="{{ $tag->name }}">{{ $tag->name }}</option>
            @endforeach
        @endif

        @if(isset($oldtags))
            @foreach($oldtags as $tag)
            <option value="{{ $tag}}">{{ $tag }}</option>
            @endforeach
        @endif
        </select>
        @if($errors->has('tags'))
            <span class="help-block">
                <strong>{{ $errors->first('tags') }}</strong>
            </span>
        @endif
    </div>
</div>

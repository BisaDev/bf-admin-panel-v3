<div class="list-group">
    @foreach($quizzes as $key => $quiz)
        <div class="list-group-item">
            <h4 class="list-group-item-heading">
                <a role="button" data-toggle="collapse" href="#collapse{{ $key }}">
                    {{ $quiz->title }}
                </a>
            </h4>
            <div id="collapse{{ $key }}" class="panel-collapse collapse">
                <p class="list-group-item-text">{{ $quiz->description }}</p>
                <div class="row m-t-15">
                    <div class="col-xs-12">
                        <ol>
                            @foreach($quiz->questions as $key => $question)
                                <li>
                                    <strong>{{ $question->title or '' }}</strong>
                                    @if($question->photo)
                                        <img src="{{ $question->photo }}" class="img-responsive thumbnail m-t-5">
                                    @endif
                                    <div class="row answer-list m-t-10">
                                        @foreach($question->answers as $answer)
                                            <div class="col-lg-3 col-sm-6 text-center answer-item">
                                                <div class="{{ ($answer->is_correct)? 'list-group-item-success' : '' }}">
                                                    @if($answer->photo)
                                                        <img src="{{ $answer->photo }}" class="img-responsive thumbnail m-b-5">
                                                    @endif
                                                    {{ $answer->text or '' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
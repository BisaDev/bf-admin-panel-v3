<div class="list-group">
    @foreach($quizzes as $key_quiz => $quiz)
        <div class="list-group-item">
            <h4 class="list-group-item-heading">
                <a role="button" data-toggle="collapse" href="#collapse{{ $key_quiz }}">
                    {{ $quiz->title }}
                </a>
            </h4>
            <div id="collapse{{ $key_quiz }}" class="panel-collapse collapse">
                <p class="list-group-item-text">{{ $quiz->description }}</p>
                <div class="row m-t-15">
                    <div class="col-xs-12">
                        <ol>
                            @foreach($quiz->questions as $key => $question)
                                <li>
                                    <strong>{{ $question->title ?? '' }}</strong>
                                    @if($question->other_photo && $question->photo)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="text-center m-t-10">{{$question->type->key == 7 ? 'Long Passage Image' : 'Equation Image'}}</p>
                                                <img src="{{ $question->other_photo }}" class="img-responsive thumbnail m-t-5">
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-center m-t-10">Question Image</p>
                                                <img src="{{ $question->photo }}" class="img-responsive thumbnail m-t-5">
                                            </div>
                                        </div>
                                    @else
                                        @if($question->other_photo)
                                            <p class="text-center m-t-10">{{$question->type->key == 7 ? 'Long Passage Image' : 'Equation Image'}}</p>
                                            <img src="{{ $question->other_photo }}" class="img-responsive thumbnail m-t-5">
                                        @endif
                                        @if($question->photo)
                                            <p class="text-center m-t-10">Question Image</p>
                                            <img src="{{ $question->photo }}" class="img-responsive thumbnail m-t-5">
                                        @endif
                                    @endif
                                    @if($question->answer_explanation || $question->answer_explanation_photo)
                                        <div class="row">
                                            <div class="col-sm-6 m-t-15">
                                                <label class="control-label">Answer Explanation: </label>
                                                <span><a href="#" data-toggle="modal" data-target="{{'#answerExplanationModal_' . $key_quiz . '_' . $key}}"><i class="ti-info-alt m-l-5"></i></a></span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row answer-list m-t-10">
                                        @foreach($question->answers as $answer)
                                            <div class="col-lg-3 col-sm-6 text-center answer-item">
                                                <div class="{{ ($answer->is_correct)? 'list-group-item-success' : '' }}">
                                                    @if($answer->photo)
                                                        <img src="{{ $answer->photo }}" class="img-responsive thumbnail m-b-5">
                                                    @endif
                                                    {{ $answer->text ?? '' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </li>
                                <div class="modal fade" id="{{ 'answerExplanationModal_' . $key_quiz . '_' . $key}}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Answer Explanation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if($question->answer_explanation)
                                                    <p>{{$question->answer_explanation}}</p>
                                                @endif
                                                @if($question->answer_explanation_photo)
                                                    <img src="{{ $question->answer_explanation_photo }}" class="modal-image center-block">
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

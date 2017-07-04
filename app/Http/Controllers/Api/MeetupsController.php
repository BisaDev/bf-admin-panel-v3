<?php

namespace Brightfox\Http\Controllers\Api;

use Brightfox\Http\Transformers\MeetupDetailsTransformer;
use Brightfox\Models\Meetup, Brightfox\Models\GradedQuiz, Brightfox\Models\GradedQuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Http\Controllers\Controller;
use Brightfox\Http\Transformers\MeetupTransformer;

class MeetupsController extends ApiController
{
    private $user;
    private $transformer;
    private $detailsTransformer;

    /**
     * UsersController constructor.
     *
     * @param \Brightfox\Http\Transformers\MeetupTransformer        $transformer
     * @param \Brightfox\Http\Transformers\MeetupDetailsTransformer $detailsTransformer
     */
    public function __construct(MeetupTransformer $transformer, MeetupDetailsTransformer $detailsTransformer)
    {
        $this->transformer = $transformer;
        $this->detailsTransformer = $detailsTransformer;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $meetups = Meetup::forUser($this->user)->forWeek()->get();
        return $this->respond($this->transformer->transformCollection($meetups));
    }

    public function show($id)
    {
        $meetup = Meetup::find($id);
        if($meetup->checkOwner($this->user)){
            return $this->respond($this->detailsTransformer->transform($meetup));
        }else{
            return $this->respondWithError('You Do not have permission to view this meetup');
        }
    }

    public function freeze($id)
    {
        $meetup = Meetup::find($id);
        
        if($meetup->checkOwner($this->user)){
            
            if($meetup->graded_quizzes->isEmpty()){
                foreach($meetup->activity_bucket->quizzes as $quiz){
                    $graded_quiz = GradedQuiz::create([
                        'quiz_id' => $quiz->id,
                        'quiz_title' => $quiz->title,
                        'quiz_description' => $quiz->description,
                        'quiz_type' => json_encode($quiz->type),
                        'quiz_grade_level' => $quiz->subject->grade_level->name,
                        'quiz_subject' => $quiz->subject->name,
                        'meetup_id' => $meetup->id
                    ]);
        
                    foreach($quiz->questions as $question){
                        GradedQuizQuestion::create([
                
                            'question_id' => $question->id,
                            'question_title' => $question->title,
                            'question_photo' => $question->getOriginal('photo'),
                            'question_topic' => $question->topic->name,
                            'answers' => $question->answers->toJson(),
                            'graded_quiz_id' => $graded_quiz->id
                        ]);
                    }
                }
    
                return $this->respond('Meetup froze correctly');
            }else{
                return $this->respondWithError('This meetup was already frozen');
            }
            
        }else{
            return $this->respondWithError('You Do not have permission to freeze this meetup');
        }
    }

}

<?php

namespace Brightfox\Http\Controllers\Api;

use Brightfox\Models\GradedQuiz;
use Brightfox\Models\GradedQuizQuestion;
use Brightfox\Models\Meetup;
use Brightfox\Models\Quiz;
use Illuminate\Http\Request;
use Brightfox\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ResultsController extends ApiController
{

    private $user;

    /**
     * ResultsController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $meetupId
     * @param                          $quizId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function gradeQuiz(Request $request, $meetupId, $quizId)
    {
        $meetup = Meetup::findOrFail($meetupId);

        if (!$meetup->checkOwner($this->user)) {
            return $this->respondWithError('You Do not have permission to Grade this meetup ');
        } elseif (!$meetup->hasQuiz($quizId)) {
            return $this->respondWithError('The quiz is not associated with the meetup');
        } else {
            $gradedQuiz = GradedQuiz::byQuizId($quizId)->first();
            $questions = collect($request->get('questions'));
            $questions->each(function ($question){
                //Find GradeQuizQuestion from question Id
                $gradedQuizQuestion = GradedQuizQuestion::find('question_id', $question['id']);
                dd($gradedQuizQuestion);
                // Validate GradeQuizQuestion belongs to GradedQuiz

            });
            return $this->respond('so far so good');
        }

    }

}

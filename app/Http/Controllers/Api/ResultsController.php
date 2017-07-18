<?php

namespace Brightfox\Http\Controllers\Api;

use Brightfox\Models\GradedQuiz;
use Brightfox\Models\GradedQuizQuestion;
use Brightfox\Models\Meetup;
use Brightfox\Models\Quiz;
use Brightfox\Models\Student;
use Brightfox\Models\StudentAnswer;
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
    public function gradeQuiz(Request $request, $meetupId, $quizId, $studentId)
    {
        $meetup = Meetup::findOrFail($meetupId);
        $student = Student::findOrFail($studentId);
        //Get Student and validate is in meetup

        if (!$meetup->checkOwner($this->user)) {
            return $this->respondWithError('You Do not have permission to Grade this meetup ');
        } elseif (!$meetup->hasQuiz($quizId)) {
            return $this->respondWithError('The quiz is not associated with the meetup');
        } elseif (!$meetup->hasStudent($student->id)) {
            return $this->respondWithError('The student does not belongs to the meetup');
        } else {
            $gradedQuiz = GradedQuiz::byQuizId($quizId)->first();
            $questions = collect($request->get('questions'));

            $questions->each(function ($question) use ($gradedQuiz, $student) {
                $gradedQuizQuestion = $gradedQuiz->questions()->findByQuestionId($question['id'])->first();
                
                $image_name = explode('/', $question['answer']['image']);
                
                $studentAnswer = StudentAnswer::updateOrCreate(
                    [
                        'graded_quiz_question_id' => $gradedQuizQuestion->id,
                        'answer_id' => $question['answer']['id'],
                        'student_id' => $student->id
                    ],
                    [
                        'answer_text' => $question['answer']['text'],
                        'answer_image' => end($image_name),
                        'is_correct' => $question['answer']['is_correct'],
                    ]);
            });

            return $this->respond('Quiz successfully graded');
        }

    }

}

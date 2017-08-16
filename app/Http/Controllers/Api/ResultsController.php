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
use Image;

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
            $gradedQuiz = GradedQuiz::byMeetupAndQuizId($meetupId, $quizId)->first();
            $questions = collect($request->get('questions'));

            $questions->each(function ($question) use ($gradedQuiz, $student) {
                $gradedQuizQuestion = $gradedQuiz->questions()->findByQuestionId($question['id'])->first();

                if($question['answer']['image'] != ''){
                    if(strpos(basename($question['answer']['image']), '.')){
                        $filename = basename($question['answer']['image']);

                        $image = Image::make(public_path(StudentAnswer::PHOTO_PATH . $filename));

                        $filename = explode('.', $filename);
                        $extension = $filename[1];
                    }else{
                        $image = Image::make($question['answer']['image']);

                        $extension = '.jpg';
                    }

                    $new_filename = str_replace('.', '_', microtime(true)) . '.' . $extension;

                    $image->save(public_path(StudentAnswer::PHOTO_PATH . $new_filename));
                }else{
                    $new_filename = '';
                }
                
                $studentAnswer = StudentAnswer::updateOrCreate(
                    [
                        'graded_quiz_question_id' => $gradedQuizQuestion->id,
                        'answer_id' => $question['answer']['id'],
                        'student_id' => $student->id
                    ],
                    [
                        'answer_text' => $question['answer']['text'],
                        'answer_image' => $new_filename,
                        'is_correct' => $question['answer']['is_correct'],
                    ]);
            });

            return $this->respond('Quiz successfully graded');
        }

    }

}

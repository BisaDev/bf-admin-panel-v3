<?php

namespace Brightfox\Http\Transformers;

use Brightfox\Models\Student;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class MeetupDetailsTransformer extends Transformer
{
    private $meetupTransformer;
    private $studentTransformer;
    private $quizTransformer;

    /**
     * MeetupDetailsTransformer constructor.
     *
     * @param \Brightfox\Http\Transformers\StudentTransformer $studentTransformer
     * @param \Brightfox\Http\Transformers\MeetupTransformer  $meetupTransformer
     * @param \Brightfox\Http\Transformers\QuizTransformer    $quizTransformer
     */
    public function __construct(StudentTransformer $studentTransformer, MeetupTransformer $meetupTransformer, QuizTransformer $quizTransformer)
    {
        $this->meetupTransformer = $meetupTransformer;
        $this->studentTransformer = $studentTransformer;
        $this->quizTransformer = $quizTransformer;
    }


    public function transform($meetup)
    {
        $transformedMeetup = $this->meetupTransformer->transform($meetup);
        $transformedMeetup += [
            'students' => $this->studentTransformer->transformCollection($meetup->students),
            'quizzes' =>  $this->quizTransformer->transformCollection($meetup->activity_bucket->quizzes)
        ];

        return $transformedMeetup;
    }

}

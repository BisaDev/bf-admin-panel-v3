<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class ActivityBucketDetailsTransformer extends Transformer
{

    private $quizTransformer;
    private $gradeLevelTransformer;

    /**
     * ActivityBucketDetailsTransformer constructor.
     *
     * @param $quizTransformer
     * @param \Brightfox\Http\Transformers\GradeLevelTransformer           $gradeLevelTransformer
     */
    public function __construct(QuizDetailsTransformer $quizTransformer, GradeLevelTransformer $gradeLevelTransformer)
    {
        $this->quizTransformer = $quizTransformer;
        $this->gradeLevelTransformer = $gradeLevelTransformer;
    }


    public function transform($activityBucket)
    {
        return [
            'id' => (int)$activityBucket->id,
            'name' => $activityBucket->title,
            'gradeLevel' => $this->gradeLevelTransformer->transform($activityBucket->subject->grade_level),
            'quizzes' => $this->quizTransformer->transformCollection($activityBucket->quizzes)
        ];
    }

}

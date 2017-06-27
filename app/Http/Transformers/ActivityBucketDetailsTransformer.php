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

    /**
     * ActivityBucketDetailsTransformer constructor.
     *
     * @param $quizTransformer
     */
    public function __construct(QuizDetailsTransformer $quizTransformer)
    {
        $this->quizTransformer = $quizTransformer;
    }


    public function transform($activityBucket)
    {
        return [
            'id' => (int)$activityBucket->id,
            'name' => $activityBucket->title,
            'gradeLevel' => 'Under Implementation',
            'quizzes' => $this->quizTransformer->transformCollection($activityBucket->quizzes)
        ];
    }

}

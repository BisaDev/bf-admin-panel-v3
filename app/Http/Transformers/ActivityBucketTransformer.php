<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class ActivityBucketTransformer extends Transformer
{
    private $gradeLevelTransformer;
    
    /**
     * MeetupTransformer constructor.
     *
     * @param \Brightfox\Http\Transformers\GradeLevelTransformer           $gradeLevelTransformer
     */
    public function __construct(GradeLevelTransformer $gradeLevelTransformer)
    {
        $this->gradeLevelTransformer = $gradeLevelTransformer;
    }

    public function transform($activityBucket)
    {
        return [
            'id' => (int)$activityBucket->id,
            'name' => $activityBucket->title,
            'gradeLevel' => $this->gradeLevelTransformer->transform($activityBucket->subject->grade_level),
        ];
    }

}

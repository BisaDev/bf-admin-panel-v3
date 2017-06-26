<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class ActivityBucketTransformer extends Transformer
{

    public function transform($activityBucket)
    {
        return [
            'id' => (int)$activityBucket->id,
            'name' => $activityBucket->title,
            'gradeLevel' => 'Under Implementation'
        ];
    }

}

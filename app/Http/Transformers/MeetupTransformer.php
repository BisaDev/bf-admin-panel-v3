<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class MeetupTransformer extends Transformer
{
    private $userTransformer;
    private $roomTransformer;
    private $activityBucketTransformer;

    /**
     * MeetupTransformer constructor.
     *
     * @param \Brightfox\Http\Transformers\UserTransformer           $userTransformer
     * @param \Brightfox\Http\Transformers\RoomTransformer           $roomTransformer
     * @param \Brightfox\Http\Transformers\ActivityBucketTransformer $activityBucketTransformer
     */
    public function __construct(UserTransformer $userTransformer, RoomTransformer $roomTransformer, ActivityBucketTransformer $activityBucketTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->roomTransformer = $roomTransformer;
        $this->activityBucketTransformer = $activityBucketTransformer;
    }

    public function transform($meetup)
    {
        return [
            'id' => (int)$meetup->id,
            'startTime' => $meetup->start_time->toDateTimeString(),
            'endTime' => $meetup->end_time->toDateTimeString(),
            'status' => $meetup->status,
            'instructor' => $this->userTransformer->transform($meetup->user),
            'room' => $this->roomTransformer->transform($meetup->room),
            'activityBucket' => (is_null($meetup->activity_bucket)) ? null : $this->activityBucketTransformer->transform($meetup->activity_bucket),
        ];
    }


}

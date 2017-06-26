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

    /**
     * MeetupTransformer constructor.
     *
     * @param \Brightfox\Http\Transformers\UserTransformer $userTransformer
     */
    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function transform($meetup)
    {
        return [
            'id' => (int)$meetup->id,
            'startTime' => $meetup->start_time->toDateTimeString(),
            'endTime' => $meetup->end_time->toDateTimeString(),
            'activity_bucket_id' => (int)$meetup->activity_bucket_id,
            'instructor' => $this->userTransformer->transform($meetup->user),
            'room' => (int)$meetup->room_id,
        ];
    }

}

<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class RoomTransformer extends Transformer
{

    public function transform($room)
    {
        return [
            'id' => (int)$room->id,
            'name' => $room->name
        ];
    }

}

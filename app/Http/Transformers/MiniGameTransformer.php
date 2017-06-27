<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class MiniGameTransformer extends Transformer
{

    public function transform($miniGame)
    {
        return [
            'id' => (int)$miniGame->id,
            'name' => $miniGame->name,
            'picture' => $miniGame->photo
        ];
    }

}

<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class IpadTransformer extends Transformer
{

    public function transform($ipad)
    {
        return [
            'id' => (int)$ipad->id,
            'macAddress' => $ipad->mac_address
        ];
    }

}

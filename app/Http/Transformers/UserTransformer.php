<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class UserTransformer extends Transformer
{

    public function transform($user)
    {
        return [
            'id' => (int)$user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

}

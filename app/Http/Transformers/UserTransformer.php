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
            'name' => $user->full_name,
            'email' => $user->email,
            'picture' => $user->user_detail->photo
        ];
    }

}

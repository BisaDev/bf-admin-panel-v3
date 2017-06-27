<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class GradeLevelTransformer extends Transformer
{

    public function transform($gradeLevel)
    {
        return [
            'id' => (int)$gradeLevel->id,
            'name' => $gradeLevel->name
        ];
    }

}

<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class StudentTransformer extends Transformer
{

    public function transform($student)
    {
        return [
            'id' => (int)$student->id,
            'name' => $student->full_name,
            'picture' => $student->photo
        ];
    }

}

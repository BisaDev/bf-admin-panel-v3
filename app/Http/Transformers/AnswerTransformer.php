<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class AnswerTransformer extends Transformer
{

    public function transform($answer)
    {
        return [
            'id' => (int)$answer->id,
            'text' => $answer->text,
            'picture' => $answer->photo,
            'isCorrect' => ($answer->is_correct) ? true : false,
            'group' => $answer->group,
            'objectData' => $answer->object_data,
        ];
    }

}

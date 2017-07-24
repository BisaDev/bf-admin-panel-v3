<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class QuestionTransformer extends Transformer
{
    private $answersTransformer;

    /**
     * QuestionTransformer constructor.
     *
     * @param $answersTransformer
     */
    public function __construct(AnswerTransformer $answersTransformer)
    {
        $this->answersTransformer = $answersTransformer;
    }

    public function transform($question)
    {
        return [
            'id' => (int)$question->id,
            'title' => $question->title,
            'picture' => $question->photo,
            'type' => $question->type,
            'answers' => $this->answersTransformer->transformCollection($question->answers)
        ];
    }

}

<?php

namespace Brightfox\Http\Transformers;

use Brightfox\Models\Minigame;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class QuizDetailsTransformer extends Transformer
{

    private $subjectTransformer;
    private $miniGameTransformer;
    private $questionTransformer;

    /**
     * QuizTransformer constructor.
     *
     * @param \Brightfox\Http\Transformers\SubjectTransformer  $subjectTransformer
     * @param \Brightfox\Http\Transformers\MiniGameTransformer $miniGameTransformer
     * @param \Brightfox\Http\Transformers\QuestionTransformer $questionTransformer
     */
    public function __construct(SubjectTransformer $subjectTransformer, MiniGameTransformer $miniGameTransformer, QuestionTransformer $questionTransformer)
    {
        $this->subjectTransformer = $subjectTransformer;
        $this->miniGameTransformer = $miniGameTransformer;
        $this->questionTransformer = $questionTransformer;
    }

    public function transform($quiz)
    {
        $miniGame = Minigame::where('id', $quiz->pivot->minigame_id)->first();
        return  [
            'id' => (int)$quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'type' => $quiz->type,
            'subject' => $this->subjectTransformer->transform($quiz->subject),
            'miniGame' => (is_null($miniGame)) ? null : $this->miniGameTransformer->transform($miniGame),
            'questions' => $this->questionTransformer->transformCollection($quiz->questions)
        ];

    }

}

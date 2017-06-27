<?php

namespace Brightfox\Http\Transformers;

use Brightfox\Models\Minigame;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class QuizTransformer extends Transformer
{

    private $subjectTransformer;
    private $miniGameTransformer;

    /**
     * QuizTransformer constructor.
     *
     * @param \Brightfox\Http\Transformers\SubjectTransformer  $subjectTransformer
     * @param \Brightfox\Http\Transformers\MiniGameTransformer $miniGameTransformer
     */
    public function __construct(SubjectTransformer $subjectTransformer, MiniGameTransformer $miniGameTransformer)
    {
        $this->subjectTransformer = $subjectTransformer;
        $this->miniGameTransformer= $miniGameTransformer;
    }

    public function transform($quiz)
    {
        //@todo Diego Fix dis shit -_-!
        $miniGame = Minigame::where('id', $quiz->pivot->minigame_id)->first();
        return [
            'id' => (int)$quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'type' => $quiz->type,
            'subject' => $this->subjectTransformer->transform($quiz->subject),
            'questions' => $quiz->questions->count(),
            'miniGame' => (is_null($miniGame)) ? null : $this->miniGameTransformer->transform($miniGame)
        ];
    }

}

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

    public function transform($quiz, $withQuestions = false)
    {
        //@todo Diego Fix dis shit -_-!
        $miniGame = Minigame::where('id', $quiz->pivot->minigame_id)->first();
        return [
            'id' => (int)$quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'type' => $quiz->type,
            'subject' => $this->subjectTransformer->transform($quiz->subject),
            'topics' => implode(',', $quiz->questions->map(function($question){
                return $question->topic->name;
            })->unique()->toArray()),
            'questions' => $quiz->questions->count(),
            'miniGame' => (is_null($miniGame)) ? null : $this->miniGameTransformer->transform($miniGame)
        ];
    }

}

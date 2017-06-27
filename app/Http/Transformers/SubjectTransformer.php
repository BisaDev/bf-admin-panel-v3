<?php

namespace Brightfox\Http\Transformers;

/**
 * Class UserTransformer
 *
 * @package \App\Http\Transformers
 */
class SubjectTransformer extends Transformer
{

    private $gradeLevelTransformer;

    /**
     * SubjectTransformer constructor.
     *
     * @param $gradeLevelTransformer
     */
    public function __construct(GradeLevelTransformer $gradeLevelTransformer)
    {
        $this->gradeLevelTransformer = $gradeLevelTransformer;
    }


    public function transform($subject)
    {
        return [
            'id' => (int)$subject->id,
            'name' => $subject->name,
            'gradeLevel' => $this->gradeLevelTransformer->transform($subject->grade_level)
        ];
    }

}

<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Http\Transformers\TaggingSubjectTransformer;
use Brightfox\Models\User;
use Brightfox\TaggingLog;
use Illuminate\Http\Request;
use Brightfox\TaggingSubject;
use Brightfox\TaggingQuestion;

class TaggingToolController extends Controller
{
    private $transformer;

    public function __construct(TaggingSubjectTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index() {
        $instructors = User::role('instructor')->get();
        $subjects = TaggingSubject::with('questions')->get();
        $subjects = $this->transformer->transform($subjects);

        return view('tagging_tool.index', compact('instructors', 'subjects'));
    }

    public function tag($subject_id) {
        $subject = TaggingSubject::find($subject_id);
        $userId = auth()->user()->id;

        return view('tagging_tool.tag', compact('subject', 'userId'));

    }

    public function set_topic(Request $request) {
        $topic_id = $request->topic_id;
        $question_id = $request->question_id;
        $instructor_id = $request->instructor_id;

        $question = TaggingQuestion::find($question_id);
        $question->tagging_topic_id = $topic_id;
        $question->save();

        $log = TaggingLog::create([
            'instructor_id' => $instructor_id,
            'tagging_question_id' => $question_id
        ]);

        return $request;
    }
}

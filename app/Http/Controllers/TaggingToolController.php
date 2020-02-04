<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Http\Transformers\TaggingLogTransformer;
use Brightfox\Http\Transformers\TaggingSubjectTransformer;
use Brightfox\Models\User;
use Brightfox\TaggingLog;
use Illuminate\Http\Request;
use Brightfox\TaggingSubject;
use Brightfox\TaggingQuestion;

class TaggingToolController extends Controller
{
    private $subjectTransformer;

    public function __construct(TaggingSubjectTransformer $subjectTransformer)
    {
        $this->subjectTransformer = $subjectTransformer;
    }

    public function index() {
        $instructors = User::role('instructor')->with('tag_logs')->get();
        $subject_stats = TaggingSubject::with('questions')->get();
        $subject_stats = $this->subjectTransformer->transformCollection($subject_stats);
        $subject_topics = TaggingSubject::with('topics')->get();

        return view('tagging_tool.index', compact('instructors', 'subject_stats' ,'subject_topics'));
    }

    public function tag($subject_id) {
        $subject = TaggingSubject::with('topics')->find($subject_id);
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

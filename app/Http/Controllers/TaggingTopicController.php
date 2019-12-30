<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingSubject, Brightfox\TaggingTopic;
use Illuminate\Http\Request;

class TaggingTopicController extends Controller
{
    public function create($subject_id)
    {
        return view('tagging_topic.create' , compact('subject_id'));
    }

    public function store(Request $request)
    {
        $subject = TaggingTopic::create($request->only(['name' , 'tagging_subject_id']));

        return redirect(route('taggingsubjects.index'));
    }

    public function edit($id)
    {
        $topic = TaggingTopic::find($id);
        $subject = TaggingSubject::find($topic->tagging_subject_id)->first();
        return view('tagging_topic.edit', compact(['topic' , 'subject']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @param \Brightfox\TaggingTopic
     * @param \Brightfox\TaggingSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $topic = TaggingTopic::find($id);
        $topic->name = $request->name;
        $topic->save();
        $subject = TaggingSubject::find($topic->tagging_subject_id);

        return redirect(route('taggingsubjects.show', compact('subject')));
    }
}

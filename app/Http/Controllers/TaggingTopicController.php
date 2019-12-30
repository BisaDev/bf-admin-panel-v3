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

        return redirect(route('taggingsubjects'));
    }

    public function edit($id)
    {
        $topic = TaggingTopic::find($id);
        return view('tagging_topic.edit', compact(['topic']));
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param \Brightfox\TaggingTopic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subject = TaggingTopic::find($id);
        $subject->name = $request->name;
        $subject->save();

        return redirect(route('taggingsubjects.index'));
    }
}

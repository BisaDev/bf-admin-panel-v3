<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingSubject, Brightfox\TaggingTopic;
use Illuminate\Http\Request;

class TaggingSubjectController extends Controller
{

    public function index()
    {
        $subjects = TaggingSubject::all();

        return view('tagging_subject.index', compact(['subjects']));
    }


    public function create()
    {

        return view('tagging_subject.create');
    }


    public function store(Request $request)
    {
        $subject = TaggingSubject::create($request->only(['name']));

        if ($request->has('topics')) {

            foreach ($request->input('topics') as $topic_name) {
                if (!is_null($topic_name)) {
                    TaggingTopic::create(['name' => $topic_name, 'tagging_subject_id' => $subject->id]);
                }
            }
        }

        return redirect(route('taggingsubjects'));

    }


    public function show($id, TaggingSubject $subject)
    {
        $subject = TaggingSubject::find($id);

        return view('tagging_subject.show', compact('subject'));
    }


    public function edit($id)
    {
        $subject = TaggingSubject::find($id);
        // return $subject;
        return view('tagging_subject.edit', compact('subject'));
    }


    public function update(Request $request, $id)
    {
        $subject = TaggingSubject::find($id);
        $subject->name = $request->name;
        $subject->save();

        return redirect(route('taggingsubjects.index'));
    }


    public function destroy($id)
    {
        //
    }
}


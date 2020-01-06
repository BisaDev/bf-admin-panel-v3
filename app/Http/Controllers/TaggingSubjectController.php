<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingSubject, Brightfox\TaggingTopic;
use Illuminate\Http\Request;
use DB;

class TaggingSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = TaggingSubject::all();

        return view('tagging_subject.index', compact(['subjects']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('tagging_subject.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

        return redirect(route('taggingsubjects.index'));

    }

    /**
     * Display the specified resource.
     * var $item
     *
     * @param \Brightfox\TaggingSubject $subject
     * @return \Illuminate\Http\Response
     */
    public function show($id, TaggingSubject $subject)
    {
        $subject = TaggingSubject::find($id);

        return view('tagging_subject.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getSubjects ()
    {
        return TaggingSubject::all();
    }
}


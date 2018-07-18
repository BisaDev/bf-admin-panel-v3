<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;

class ExamPrepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('web.exam_prep.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.exam_prep.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $csvFile = $request->file('csv');
        $path = $csvFile->store('/data');
        $examArray = $this->createArray(storage_path('app/' . $path));
        dd($examArray);
        return view('web.exam_prep.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function createArray($file)
    {
        $feed = $file;
        $keys[] = [];
        $data = $this->csvToArray($feed);
        $count = count($data) - 2;
        $examType = $data[0][0];
        $keys = $data[1];
        $data = array_slice($data, 2);
        $tmpData = [];

        $keys[] = 'id';
        for ($i = 0; $i < $count; $i++) {
            $data[$i][] = $i+1;
        }

        for ($j = 0; $j < $count; $j++) {
            $d = array_combine($keys, $data[$j]);
            $tmpData[$j] = $d;
        }

        array_unshift($tmpData, $examType);

        return $tmpData;
    }

    public function csvToArray($file)
    {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, ",", '"')) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        return $arr;
    }
}

<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingImage;
use Brightfox\TaggingQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{

    public function index()
    {
        return view('image_upload.index');
    }

    public function upload(Request $request)
    {
        $images = [];
        $subject = $request->subject;

        foreach ($request->all() as $key => $value) {
             if (strpos($key, "_") != false) {
                $keys = explode("_", $key);

                if (strstr($key, "questionImg") && $value != "null") {
                    $images[$keys[1]]["imageFiles"]['question'] = $value;
                }
                if (strstr($key, "explanationImg") && $value != "null") {
                    $images[$keys[1]]["imageFiles"]['explanation'] = $value;
                }
                 if (strstr($key, "answer") && $value != "null") {
                     $images[$keys[1]]["answer"] = $value;
                 }
            }
        }

        DB::beginTransaction();

        try {
            $path = "tt_images/";
            $taggingQuestion = TaggingQuestion::create(["tagging_subject_id" => $request->subjectID]);
            $questionId = $taggingQuestion->id;

            foreach ($images as $index=> $image) {
                $answer = $image['answer'];
                $imageUrl = "";
                $explanationUrl = "";

                foreach ($image['imageFiles'] as $questionType => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = "$questionId-$subject-$questionType-$answer-$index.$extension";
                    $save = Storage::disk('public')->put("$path$fileName", file_get_contents($value));
                    if(!$save) {
                        throw new \Exception("Upload Error");
                    }

                    if($questionType == 'question') {
                        $imageUrl = $fileName;
                    } else if($questionType == 'explanation') {
                        $explanationUrl = $fileName;
                    }
                }

                TaggingImage::create([
                    'image_answer'          => $answer,
                    'image_url'             => $imageUrl,
                    'tagging_question_id'   => $questionId,
                    'explanation_url'       => $explanationUrl
                ]);
            }

            DB::commit();
            return "Success";

        } catch (\Throwable $e) {
            DB::rollback();
            return $e;
        }
    }
}

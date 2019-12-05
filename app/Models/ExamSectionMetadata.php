<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSectionMetadata extends Model
{
    protected $table = "exam_section_metadatas";

    protected $fillable = [
        'exam_type', 'section_number', 'section_name', 'questions', 'open_questions', 'time_available', 'table_score', 'max_score'
    ];
}

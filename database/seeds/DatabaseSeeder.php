<?php

use Brightfox\Models\ExamSectionMetadata;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'student']);
        Role::create(['name' => 'director']);
        Role::create(['name' => 'instructor']);

        ExamSectionMetadata::create([
            'exam_type' => 'SAT',
            'section_number' => 1,
            'section_name' => 'Reading Comprehension',
            'questions' => 52,
            'open_questions' => 0,
            'time_available' => 65,
            'table_score' => 'Reading Section Score',
            'max_score' => 400,
        ]);

        ExamSectionMetadata::create([
            'exam_type' => 'SAT',
            'section_number' => 2,
            'section_name' => 'Writing and Language',
            'questions' => 44,
            'open_questions' => 0,
            'time_available' => 35,
            'table_score' => 'Writing Section Score',
            'max_score' => 400,
        ]);

        ExamSectionMetadata::create([
            'exam_type' => 'SAT',
            'section_number' => 3,
            'section_name' => 'Math-No Calculator',
            'questions' => 20,
            'open_questions' => 5,
            'time_available' => 25,
            'table_score' => 'Math Section Score',
            'max_score' => 800,
        ]);

        ExamSectionMetadata::create([
            'exam_type' => 'SAT',
            'section_number' => 4,
            'section_name' => 'Math-With Calculator',
            'questions' => 38,
            'open_questions' => 8,
            'time_available' => 55,
            'table_score' => 'Math Section Score',
            'max_score' => 800,
        ]);

        ExamSectionMetadata::create([
            'exam_type' => 'ACT',
            'section_number' => 1,
            'section_name' => 'English',
            'questions' => 75,
            'time_available' => 45,
            'table_score' => 'English Section Score',
            'max_score' => 36,
        ]);

        ExamSectionMetadata::create([
            'exam_type' => 'ACT',
            'section_number' => 2,
            'section_name' => 'Mathematics',
            'questions' => 60,
            'time_available' => 60,
            'table_score' => 'Math Section Score',
            'max_score' => 36,
        ]);

        ExamSectionMetadata::create([
            'exam_type' => 'ACT',
            'section_number' => 3,
            'section_name' => 'Reading',
            'questions' => 40,
            'time_available' => 35,
            'table_score' => 'Reading Section Score',
            'max_score' => 36,
        ]);

        ExamSectionMetadata::create([
            'exam_type' => 'ACT',
            'section_number' => 4,
            'section_name' => 'Science',
            'questions' => 40,
            'time_available' => 35,
            'table_score' => 'Science Section Score',
            'max_score' => 36,
        ]);

    }
}

<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\Student, Brightfox\Models\Location, Brightfox\Models\Note, Brightfox\Models\User, Brightfox\Models\UserDetail;
use Illuminate\Http\Request;
use Brightfox\Traits\CreatesAndSavesPhotos;
use Carbon\Carbon;
use File;

class StudentController extends Controller
{
    use CreatesAndSavesPhotos;
    protected $genders = Student::GENDERS;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('search')){
            $search = $request->input('search');
            $list = Student::search($search)->paginate(50);
        }else{
            $list = Student::paginate(50);
        }

        return view('web.students.index', compact('list', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.students.create', [
            'locations' => Location::all(),
            'genders' => $this->genders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'photo' => 'nullable|image',
            'birthdate' => 'nullable|date_format:m/d/Y',
            'location' => 'required'
        ]);
        
        if($request->input('birthdate')){
            $birthdate = Carbon::createFromFormat('m/d/Y', $request->input('birthdate'));
            $birthdate_format = $birthdate->toDateString();
        }else{
            $birthdate_format = null;
        }

        $student = Student::create([
            'name' => $request->input('name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'birthdate' => $birthdate_format,
            'gender' => json_encode(['key' => $request->input('gender'), 'name' => $this->genders[$request->input('gender')]], JSON_FORCE_OBJECT),
            'school_year' => $request->input('school_year'),
            'current_school' => $request->input('current_school'),
            'teacher' => $request->input('teacher'),
            'former_school' => $request->input('former_school'),
            'location_id' => $request->input('location')
        ]);

        if ($request->hasFile('photo')) {
            $student->photo = $this->createAndSavePhoto($request->file('photo'), Student::PHOTO_PATH);
            $student->save();
        }

        if($request->has('notes')){
            foreach ($request->input('notes') as $note) {
                if(!is_null($note['title']) && !is_null($note['text'])){
                    $note = Note::create(['title' => $note['title'], 'text' => $note['text']]);
                    $student->notes()->save($note);
                }
            }
        }

        if($request->input('add_user')) {

            $this->validate($request, [
                'email' => 'required|unique:users|email',
                'email_confirmation' => 'same:email',
                'password' => 'required',
                'password_confirmation' => 'same:password',
                'secondary_email' => 'nullable|email',
            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'student_id' => $student->id,
                'password'  => bcrypt($request->input('password'))
            ]);

            UserDetail::create([
                'secondary_email' => $request->input('secondary_email'),
                'phone' => $request->input('phone'),
                'mobile_phone' => $request->input('mobile_phone'),
                'location_id' => $request->input('location'),
                'user_id' => $user->id
            ]);

            $user->assignRole('student');
            $user->save();
            $student->user_id = $user->id;
            $student->save();

        }


        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Student was successfully created']);
        
        return redirect(route('students.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Student $student)
    {
        $item = $student;

        if($request->has('search')){
            $search = $request->input('search');
            $item->family_members = $item->family_members()->search($search)->paginate(50);
        }else{
            $item->family_members = $item->family_members()->paginate(50);
        }

        return view('web.students.show', compact('item', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return view('web.students.edit', [
            'item' => $student,
            'locations' => Location::all(),
            'genders' => $this->genders
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'photo' => 'nullable|image',
            'birthdate' => 'nullable|date_format:m/d/Y',
            'location' => 'required'
        ]);

        if($request->input('birthdate')){
            $birthdate = Carbon::createFromFormat('m/d/Y', $request->input('birthdate'));
            $birthdate_format = $birthdate->toDateString();
        }else{
            $birthdate_format = null;
        }
        
        $student->name = $request->input('name');
        $student->middle_name = $request->input('middle_name');
        $student->last_name = $request->input('last_name');
        $student->birthdate = $birthdate_format;
        $student->gender = json_encode(['key' => $request->input('gender'), 'name' => $this->genders[$request->input('gender')]], JSON_FORCE_OBJECT);
        $student->school_year = $request->input('school_year');
        $student->current_school = $request->input('current_school');
        $student->teacher = $request->input('teacher');
        $student->former_school = $request->input('former_school');
        $student->location_id = $request->input('location');
        $student->save();

        if ($request->hasFile('photo')) {
            if(!is_null($student->getOriginal('photo')) || $student->getOriginal('photo') != ''){
                File::delete(public_path(Student::PHOTO_PATH . $student->getOriginal('photo')));
            }

            $student->photo = $this->createAndSavePhoto($request->file('photo'), Student::PHOTO_PATH);
            $student->save();
        }

        if($request->has('notes')){
            $notes_ids = collect($request->get('notes'))->map(function($note){
                return $note['id'];
            })->toArray();

            $notes_to_delete = $student->notes()->whereNotIn('id', $notes_ids)->delete();

            foreach ($request->input('notes') as $key => $request_note) {
                if(!is_null($request_note['id'])){

                    $note = Note::find($request_note['id']);
                    $note->title = $request_note['title'];
                    $note->text = $request_note['text'];
                    
                    $note->save();
                }else{
                    $note = Note::create(['title' => $request_note['title'], 'text' => $request_note['text']]);
                    $student->notes()->save($note);
                }
            }
        }else{
            $student->notes()->delete();
        }

        if ($student->user) {
            $user = User::find($student->user_id);

            $this->validate($request, [
                'email' => 'required|unique:users,email,'.$user->id.'|email',
                'secondary_email' => 'nullable|email',
                'password' => 'sometimes|nullable',
                'password_confirmation' => 'sometimes|same:password',
                'photo' => 'nullable|image',
            ]);

            $user->name = $request->input('name');
            $user->middle_name = $request->input('middle_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');

            if($request->has('password')){
                $user->password = bcrypt($request->input('password'));
            }

            $user->user_detail->secondary_email = $request->input('secondary_email');
            $user->user_detail->phone = $request->input('phone');
            $user->user_detail->mobile_phone = $request->input('mobile_phone');

            $user->user_detail->save();
            $user->save();
        }



        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Student was successfully edited']);
        
        return redirect(route('students.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Brightfox\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Student $student)
    {
        if ($student->user) {
            $user = User::find($student->user_id);
            $user->delete();
        }

        $student->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Student was successfully deleted']);
        
        return redirect(route('students.index'));
    }
    
    public function save_notes(Request $request, Student $student)
    {
        if($request->has('notes')){
            $notes_ids = collect($request->get('notes'))->map(function($note){
                return $note['id'];
            })->toArray();
            
            $student->notes()->whereNotIn('id', $notes_ids)->delete();
            
            foreach ($request->input('notes') as $key => $request_note) {
                if(!is_null($request_note['id'])){
                    
                    $note = Note::find($request_note['id']);
                    $note->title = $request_note['title'];
                    $note->text = $request_note['text'];
                    
                    $note->save();
                }else{
                    $note = Note::create(['title' => $request_note['title'], 'text' => $request_note['text']]);
                    $student->notes()->save($note);
                }
            }
        }else{
            $student->notes()->delete();
        }
    
        return redirect(route('students.show', $student->id));
    }
    
    public function student_progress_print(Student $student)
    {
        $student_data = [];
        $total_questions = 0;
        
        foreach($student->meetups->reverse() as $meetup){
            foreach($meetup->graded_quizzes as $graded_quiz){
        
                $graded_quiz_answers = $student->graded_answers($graded_quiz->id)->get();
        
                foreach($graded_quiz_answers as $student_answer){
                    
                    $subject = $graded_quiz->quiz_subject;
                    $topic = $student_answer->graded_quiz_question->question_topic;
                    
                    if(!array_key_exists($subject, $student_data)){
                        $student_data[$subject] = [];
                    }
                    if(!array_key_exists($topic, $student_data[$subject])){
                        $student_data[$subject][$topic] = [];
                    }
                    
                    $tags = collect(json_decode($student_answer->graded_quiz_question->tags));
                    
                    $level_tags = $tags->filter(function($value, $key){
                        return strpos($value->name, 'lvl-') !== false;
                    });
                    
                    //foreach($level_tags as $level_tag){
                        //$level = $graded_quiz->quiz_grade_level.'-'.substr($level_tag->name, 4, 1);
                        $level = $graded_quiz->quiz_grade_level;
                        
                        if(!array_key_exists($level, $student_data[$subject][$topic])){
                            $student_data[$subject][$topic][$level] = [
                                'total_questions' => 0,
                                'total_correct' => 0,
                                'percentage_correct' => 0,
                                'date_mastered' => 'N/A'
                            ];
                        }
                        
                        $student_data[$subject][$topic][$level]['total_questions'] += 1;
                        $total_questions += 1;
                        if($student_answer->is_correct){
                            $student_data[$subject][$topic][$level]['total_correct'] += 1;
                        }
    
                        $percentage_correct = round((100 * $student_data[$subject][$topic][$level]['total_correct'])/$student_data[$subject][$topic][$level]['total_questions']);
    
                        if($student_data[$subject][$topic][$level]['total_questions'] >= 200 && $student_data[$subject][$topic][$level]['date_mastered'] == 'N/A'){
        
                            if($percentage_correct > 85){
                                $student_data[$subject][$topic][$level]['date_mastered'] = $meetup->start_time->format('F jS, Y');
                            }
                        }
    
                        $student_data[$subject][$topic][$level]['percentage_correct'] = $percentage_correct;
                    //}
                }
            }
        }
    
        $meetup_hours = ['total' => 0, 'subjects' => []];
        foreach($student->meetups as $meetup){
            $subject = $meetup->graded_quizzes()->first();
            if(!is_null($subject)){
                if(!array_key_exists($subject->quiz_subject, $meetup_hours)){
                    $meetup_hours['subjects'][$subject->quiz_subject] = 0;
                }
    
                $time = new Carbon($meetup->start_time);
                $end_time =new Carbon($meetup->end_time);
                $meeting_time = $time->diffInHours($end_time);
                $meetup_hours['total'] += $meeting_time;
                $meetup_hours['subjects'][$subject->quiz_subject] += $meeting_time;
            }
        }
        
        //dd($student_data);
        return view('web.students.progress', compact('student', 'student_data', 'meetup_hours', 'total_questions'));
    }
}

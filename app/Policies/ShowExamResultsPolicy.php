<?php

namespace Brightfox\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Brightfox\Models\StudentExam, Brightfox\Models\User;

class ShowExamResultsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, StudentExam $studentExam)
    {
        $student = $user->student;
        return $student->id === $studentExam->student_id;
    }
}

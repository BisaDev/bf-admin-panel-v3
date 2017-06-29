<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Brightfox\Traits\Noteable;

class MeetupStudent extends Model
{
    use Noteable;
    
    protected $table = 'meetup_student';
    
    public function meetups()
    {
        return $this->belongsTo(Meetup::class);
    }

    public function students()
    {
        return $this->belongsTo(Student::class);
    }
}

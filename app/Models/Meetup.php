<?php

namespace Brightfox\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Meetup extends Model
{
    const STATUS = [
        'Incomplete',
        'Ready',
        'Done',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time', 'end_time', 'activity_bucket_id', 'user_id', 'room_id'
    ];

    protected $dates = [
        'start_time', 'end_time'
    ];

    public function activity_bucket()
    {
        return $this->belongsTo(ActivityBucket::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
    
    public function graded_quizzes()
    {
        return $this->hasMany(GradedQuiz::class);
    }

    public function scopeForUser($query, $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeForWeek($query)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        return $query->where('start_time', '>', $startOfWeek)->where('start_time', '<', $endOfWeek);
    }

    public function checkOwner($user)
    {
        if ($user->id === $this->user_id) {
            return true;
        }

        return false;
    }

    public function getStatusAttribute($value)
    {
        return json_decode($value);
    }
}

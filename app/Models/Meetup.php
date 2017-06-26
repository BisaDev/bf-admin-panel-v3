<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class Meetup extends Model
{
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

    public function scopeForUser($query, $user)
    {
        return $query->where('user_id', $user->id);
    }
}

<?php

namespace Brightfox\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Sofa\Eloquence\Eloquence;
use Brightfox\Traits\HasFullName;

class User extends Authenticatable
{
    use Notifiable, HasRoles, Eloquence, HasFullName;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'middle_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name', 'last_name', 'email', 'user_detail.secondary_email', 'user_detail.phone', 'user_detail.mobile_phone', 'user_detail.location.name', 
    ];

    public function user_detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

}

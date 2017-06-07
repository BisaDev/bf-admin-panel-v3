<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class Minigame extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}

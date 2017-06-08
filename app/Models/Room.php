<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Room extends Model
{
    use Eloquence;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'location_id'
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}

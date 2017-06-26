<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Topic extends Model
{
    use Eloquence;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'subject_id'
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

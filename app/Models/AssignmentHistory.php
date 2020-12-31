<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentHistory extends Model
{
    use SoftDeletes;

    protected $table = 'assignment_history';
    protected $primaryKey = 'id';
    protected $dateFormat = 'd-m-Y';

    protected $fillable = [
        'assignment_id',
        'changed_date',
        'status_id',
        'create_by'
    ];

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }

    public function createBy()
    {
        return $this->belongsTo('App\Models\User', 'create_by');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $table = 'status';
    protected $primaryKey = 'id';
    protected $dateFormat = 'd-m-Y';

    public function assignmentHistory()
    {
        return $this->hasMany('App\Models\AssignmentHistory', 'status_id');
    }
    public function assignment()
    {
        return $this->hasMany('App\Models\Assignment', 'status_id');
    }
    public function request()
    {
        return $this->hasMany('App\Models\Request', 'status_id');
    }
}

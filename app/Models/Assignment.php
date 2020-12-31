<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    protected $table = 'assignment';
    protected $fillable = [
        'user_id',
        'asset_id',
        'started_date',
        'end_date',
        'status_id',
        'created_by',
        'is_assigned',
    ];
    public function status()
    {
        return $this->belongsTo('App\Models\Status','status_id');
    }
    public function assignmentHistory()
    {
        return $this->hasMany('App\Models\AssignmentHistory', 'assignment_id');
    }
    public function requestAssignment()
    {
        return $this->hasMany('App\Models\Request', 'assignment_id');
    }
    public function request()
    {
        return $this->hasMany('App\Models\Request', 'assignment_id');
    }
    public function asset()
    {
        return $this->belongsTo('App\Models\Asset', 'asset_id');
    }
    public function userAssignment()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function createBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
    public function assignBy()
    {
        $list = $this->belongsTo('App\Models\User', 'create_by')
        ;
        //$name = implode('',$list);
        return $list;
    }
    public function scopeUserId($query, $request)
    {
        if ($request->has('UserId')) {
            $name = $request->input('UserId');
            $query->whereHas('user', function ($q) use ($name){
                $q->where('staff_code','like','%'.$name.'%')
                    ->orWhere('name','like','%'.$name.'%');
            })->orWhere('user_id','=',$name);
        }
        return $query;
    }
    public function scopeAssetId($query, $request)
    {
        if ($request->has('AssetId')) {
            $name = $request->input('AssetId');
            $query->whereHas('asset',function ($q) use ($name) {
                $q->where('asset_code','like','%'.$name.'%')
                    ->orWhere('name','like','$'.$name.'%');
            })->orWhere('asset_id','=',$name);
        }
        return $query;
    }
    public function scopeStartedDate($query, $request)
    {
        if ($request->has('StartedDate')) {
            $query->where('started_date', 'LIKE', '%' . $request->StartedDate . '%');
        }
        return $query;
    }
    public function scopeEndDate($query, $request)
    {
        if ($request->has('EndDate')) {
            $query->where('end_date', 'LIKE', '%' . $request->EndDate . '%');
        }
        return $query;
    }
    public function scopeStatus($query, $request)
    {
        if ($request->has('Status')) {
            $status_id = $request->input('Status');
            if($status_id === '1')
            {
                $query->where(['status_id' => 1, 'is_assigned' => 0]);
            }
            else if($status_id === '5')
            {
                $query->where(['status_id' => 1, 'is_assigned' => 1]);
            }
            else
            {
                $query->where(['status_id' => $status_id]);
            }
        }
        return $query;
    }
    public function scopeCreateBy($query, $request)
    {
        if ($request->has('CreateBy')) {
            $name = $request->input('CreateBy');
            $query->whereHas('createBy', function ($q) use ($name){
                $q->where('staff_code','like','%'.$name.'%')
                    ->orWhere('name','like','%'.$name.'%');
            })->orWhere('user_id','=',$name);
        }
        return $query;
    }
    public function scopeAssignTo($query, $request)
    {
        if ($request->has('AssignTo')) {
            $query->where('users.name', 'LIKE', '%' . $request->AssignTo . '%');
        }
        return $query;
    }
    public function scopeAssignBy($query, $request)
    {
        if ($request->has('AssignBy')) {
            $query->where('create_by', 'LIKE', '%' . $request->AssignBy . '%');
        }
        return $query;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requests extends Model
{
    use SoftDeletes;

    protected $table = 'request';
    protected $fillable = [
        'user_id',
        'assignment_id',
        'asset_category_id',
        'requested_date',
        'response_date',
        'created_by',
        'status',
        'deleted_at',
    ];

    public function scopeUserId($query, $request)
    {
        if ($request->has('user_id')) {
            $name = $request->input('user_id');
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%')
                    ->orWhere('staff_code', 'like', '%' . $name . '%');
            })->orWhere('user_id','=',$name);
        }
        return $query;
    }

    public function scopeAssignment($query, $request)
    {
        if ($request->has('assignment_id')) {
            $name  = $request->input('assignment_id');
            $query->whereHas('assignment', function ($q) use ($name) {
              $q->whereHas('asset', function ($que) use ($name) {
                  $que->where('name', 'like', '%' . $name . '%')
                      ->orWhere('asset_code', 'like', '%' . $name . '%');
                });
            })->orWhere('assignment_id', $request->input('assignment_id'));
        }
        return $query;
    }
    public function scopeReturn($query, $request)
    {
        if ($request->has('return')) {
            if($request->input('return') === '0')
            {
                $query->with('user')
                    ->where('assignment_id', '=', null);
            }
            else if($request->input('return') === '1')
            {
                $query->with('user')
                    ->where('asset_category_id', '=', null);
            }
        }
        return $query;
    }
    public function scopeAssetCategory($query, $request)
    {
        if ($request->has('category_id')) {
            $query->where('asset_category_id','=',$request->input('category_id'));
        }
        return $query;
    }
    public function scopeRequestedDate($query, $request)
    {
        if ($request->has('requested_date')) {
            $query->where('requested_date', $request->input('requested_date'));
        }
        return $query;
    }
    public function scopeResponseDate($query, $request)
    {
        if ($request->has('response_date')) {
            $query->where('response_date', $request->input('response_date'));
        }
        return $query;
    }
    public function scopeStatus($query, $request)
    {
        if ($request->has('status')) {
            $query->where('status', $request->input('status'))
                    ->orderByDesc('asset_category_id');
        }
        return $query;
    }
    public function scopeCreateBy($query, $request)
    {
        if ($request->has('created_by')) {
            $name = $request->input('created_by');
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%')
                    ->orWhere('staff_code', 'like', '%' . $name . '%');
            })->orWhere('user_id','=',$name);
        }
        return $query;
    }
    public function scopeAssetCode($query, $request)
    {
        if ($request->has('asset_code')) {
            $name = $request->input('asset_code');
            $query->whereHas('assignment', function ($q) use ($name) {
                $q->whereHas('asset',function($que) use ($name){
                    $que->where('asset_code', 'like', '%' . $name . '%');
                });
            });
        }
        return $query;
    }
    public function scopeAssetName($query, $request)
    {
        if ($request->has('asset_name')) {
            $name = $request->input('asset_name');
            $query->whereHas('assignment', function ($q) use ($name) {
                $q->whereHas('asset',function($que) use ($name){
                    $que->where('name', 'like', '%' . $name . '%');
                });
            });
        }
        return $query;
    }
    public function scopeRequest($query, $request)
    {
        if ($request->has('return')) {
            $query->with('user')
                ->where('assignment_id', '!=', null);
        }
        return $query;
    }
    public function scopeAdminReturnRequest($query, $request)
    {
        if ($request->has('admin_request')) {
            $query->with('user')
                    ->whereColumn('user_id', '!=', 'created_by')
                    ->where('assignment_id', '!=', null);
        }
        return $query;
    }
    public function scopeStaffRequireRequest($query, $request)
    {
        if ($request->has('require_request')) {
            $query->with('user')->with('category')
                    ->whereColumn('user_id', '=', 'created_by')
                   ->where('asset_category_id', '!=', null);
        }
        return $query;
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function assignment()
    {
        return $this->belongsTo('App\Models\Assignment', 'assignment_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\AssetCategory', 'asset_category_id');
    }
    public function createBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status');
    }
}

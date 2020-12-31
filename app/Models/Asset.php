<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Asset extends Model
{
    use SoftDeletes;

    protected $table = 'asset';
    protected $guarded = [];

    protected $fillable  = [
        'id',
        'name',
        'asset_code',
        'location_id',
        'asset_category_id',
        'description',
        'installed_date',
        'is_delete'
    ];
    public function assetDetail()
    {
        return $this->hasMany('App\Models\AssetDetail', 'asset_id')
                    ->join('specification', 'specification.id', '=', 'asset_detail.specification_id');
    }

    public function assignment()
    {
        return $this->hasOne('App\Models\Assignment', 'asset_id')
                    ->orderBy('assignment.status_id')
                    ->where('assignment.end_date','=',null);
    }
    public function assetCategory()
    {
        return $this->belongsTo('App\Models\AssetCategory', 'asset_category_id');
    }
    public function assetLocation()
    {
        return $this->belongsTo('App\Models\Location', 'location_id');
    }
    public function assetUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function assetCreateBy()
    {
        return $this->belongsTo('App\Models\User', 'create_by');
    }
    public function scopeName($query, $request)
    {
        if ($request->has('asset_name')) {
            $query->where('name', 'LIKE', '%' . $request->input('asset_name') . '%');
        }
        return $query;
    }

    public function scopeCategory($query, $request)
    {
        if ($request->has('asset_category_id')) {
            $query->where('asset_category_id', $request->input('asset_category_id'));
        }
        return $query;
    }

    public function scopeInstall($query, $request)
    {
        if ($request->has('installed_date')) {
            $query->where('installed_date', $request->input('installed_date'));
        }
        return $query;
    }



    public function scopeByCreateBy($query, $request)
    {
        if ($request->has('is_delete')) {
            $query->where('is_delete', $request->input('is_delete'));
        }
        return $query;
    }
    public function scopeLocation($query, $request)
    {
        if ($request->has('location')) {
            $query->where('location_id', $request->input('location'));
        }
        return $query;
    }
    public function scopeCreateBy($query, $request)
    {
        if ($request->has('create_by')) {
            $name = $request->input('create_by');
            $query->whereHas('assetCreateBy', function ($q) use ($name){
                $q->where('staff_code','like','%'.$name.'%')
                    ->orWhere('name','like','%'.$name.'%');
            })->orWhere('create_by','=',$name);
        }
        return $query;
    }
    public function scopeIsDelete($query, $request)
    {
        if ($request->has('is_delete')) {
            $query->where('is_delete', $request->input('is_delete'));
        }
        return $query;
    }
    public function scopeAssetCode( $query, $request) {
        if($request->has('asset_code')){
            $name = $request->input('asset_code');
            $query->where('asset_code', 'LIKE', '%' . $name . '%');
        }
        return $query;
    }
    public function scopeSpec ( $query, $request) {
        if($request->has('specifications'))
        {
                foreach($request->all() as $keys => $values) {
                    if (strpos($keys, 'specification')!== false && strpos($keys, 'specifications') === false) {
                        $k = str_replace('specification', '', $keys);
                        $v = $values;
                        $query->whereExists(function ($q) use ($k, $v, $request) {
                            $q->select(DB::raw(1))
                                ->from('asset_detail')
                                ->whereColumn('asset_detail.asset_id', 'asset.id')
                                ->where('asset_detail.specification_id', $k)
                                ->where('asset_detail.value','like','%'.$v.'%');
                        });
                    }
                }
       }
       return $query;
    }
    public function scopeStatus ( $query, $request) {
        if($request->has('status_id')){
            if($request->input('status_id') == 1){
                $query->has('assignment', '=' ,0);
            }
            else{
                $query->has('assignment', '!=' ,0);
            }
        }
        return $query;
    }

    public function scopeOfId($id)
    {
        return DB::table('asset')
            ->join('location', 'location.id', '=', 'asset.location_id')
            ->join('asset_category', 'asset.asset_category_id', '=', 'asset_category.id')
            ->select('asset.*','asset.id as id', 'location.name as location', 'asset_category.name as category')
            ->where('id','=', $id);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specification extends Model
{
    use SoftDeletes;

    protected $table = 'specification';
    protected $primaryKey = 'id';
    protected $dateFormat = 'd-m-Y';

    protected $fillable = [
        'id',
        'name',
        'is_active'
    ];

    public function assetDetail()
    {
        return $this->hasMany('App\Models\AssetDetail', 'specification_id');
    }
    public function categorySpecification()
    {
        return $this->hasMany('App\Models\CategorySpecification', 'specification_id');
    }
    public function scopeOfType($query,$type)
    {
        return $query->where('is_required',$type);
    }
}

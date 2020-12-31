<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetCategory extends Model
{
    use SoftDeletes;

    protected $table = 'asset_category';
    protected $primaryKey = 'id';
    protected $dateFormat = 'd-m-Y';
    protected $fillable = [
        'id',
        'name',
        'is_active',
        'category_code'
    ];
    public function asset()
    {
        return $this->hasMany('App\Models\Asset', 'asset_category_id');
    }
    public function categorySpecification()
    {
        return $this->hasMany('App\Models\CategorySpecification', 'asset_category_id');
    }
    public function request()
    {
        return $this->belongstoMany('App\Models\Request');
    }
    public function scopeExtendById($type, $query)
    {
        return $query->where('id', '!=', $type);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asset_id',
        'specification_id',
        'value'
    ];

    public function test()
    {
        return $this->hasMany('App\Models\Specification', 'specification_id');
    }
    public function specification()
    {
        return $this->belongsTo('App\Models\Specification', 'specification_id');
    }

    protected $table = 'asset_detail';
    protected $primaryKey = 'id';
}

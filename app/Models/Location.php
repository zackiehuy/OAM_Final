<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    use SoftDeletes;

    protected $table = 'location';
    protected $primaryKey = 'id';
    protected $dateFormat = 'd-m-Y';

    protected $fillable = [
        'id',
        'name',
        'is_active'
    ];

    public function user()
    {
        return $this->hasMany('App\Models\User', 'location_id');
    }
    public function asset()
    {
        return $this->hasMany('App\Models\Asset', 'location_id');
    }
    public function scopeExtendById($type, $query)
    {
        return $query->where('id', '!=', $type);
    }
}

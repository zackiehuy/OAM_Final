<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CategorySpecification extends Model
{
    use SoftDeletes;

    protected $table = 'category_specification';
    protected $primaryKey = 'id';
    protected $dateFormat = 'd-m-Y';

    protected $fillable = [
        'asset_category_id',
        'specification_id',
        'default_value'
    ];

    public function scopeOfCategorySpecification()
    {
        return DB::table('category_specification')
            ->join(
                'specification',
                'category_specification.specification_id',
                '=',
                'specification.id'
            )->select(
                'category_specification.*',
                'specification.id as specification_id',
                'specification.name',
                'specification.is_required as require'
            )->where(
                'specification.is_required',
                '=',
                true,
            );
    }
}

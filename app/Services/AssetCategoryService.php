<?php

namespace App\Services;

use App\Models\AssetCategory;

class AssetCategoryService extends BaseService
{

    public function model(){
        return AssetCategory::class;
    }
    public function getAllCategory()
    {
           $query = AssetCategory::query();
           return $this->getAll($query);
    }
    public function getExtendById($id)
    {
        $query = AssetCategory::extendById($id);
        return $this->getById($id, $query);
    }
}

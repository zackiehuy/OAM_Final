<?php


namespace App\Services;


use App\Models\CategorySpecification;

class CategorySpecificationService extends BaseService
{
    public function model(){
        return CategorySpecification::class;
    }
    public function getRequireSpecification(){
        $query = (new \App\Models\CategorySpecification)->OfCategorySpecification();
        return $this->getAll($query);
    }
}

<?php


namespace App\Services;


use App\Models\Specification;

class SpecificationService extends BaseService
{
    public function model(){
        return Specification::class;
    }
    public function getOptionSpecification(){
        $query = Specification::ofType(false);
        return $this->getAll($query);
    }
}

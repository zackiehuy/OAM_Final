<?php

namespace App\Services;

use App\Models\Location;

class LocationService extends BaseService
{
    public function model(){
        return Location::class;
    }
    public function getAllLocation()
    {
        $query = Location::query();
        return $this->getAll($query);
    }
}

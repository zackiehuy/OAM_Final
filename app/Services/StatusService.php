<?php

namespace App\Services;

use App\Models\Status;

class StatusService extends BaseService
{
    public function model(){
        return Status::class;
    }
    public function getAllStatus()
    {
        $query = Status::query();
        return $this->getAll($query);
    }
}

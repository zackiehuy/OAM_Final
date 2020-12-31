<?php

namespace App\Services;

use App\Models\AssignmentHistory;

class AssignmentHistoryService extends BaseService
{
    public function model(){
        return AssignmentHistory::class;
    }

    public function getAssignmentHistoryById($id)
    {
        return AssignmentHistory::query()
                    ->where('assignment_id',$id)
                    ->with('createBy')
                    ->with('status')
                    ->orderBy('changed_date','desc')
                    ->first();
    }
}

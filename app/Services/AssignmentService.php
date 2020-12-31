<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AssignmentService extends BaseService
{
    public function model(){
        return Assignment::class;
    }
    public function __construct()
    {
        $this->mapping = collect([
            'User' => 'user_id',
            'Status' => 'status_id',
            'Asset' => 'asset_id',
            'StartedDate' => 'started_date',
            'EndDate' => 'end_date',
            'CreateBy' => 'created_by'
        ]);
    }

    public function getAllAssignments(Request $request)
    {
        $query = $this->filterColumn($request);
        $data = $this->mapping($request, $this->mapping);
        $result = $this->getSort($query, $data);
        if($data['column'] !== 'status_id')
        {
            $result->orderBy('status_id','asc');
        }
        return $result->paginate($data['limit']);
    }
    public function createAssignment($request)
    {
        $checkAlive = Assignment::query()
                        ->where(['status_id' => 2 , 'asset_id' => $request['asset_id']])
                        ->get();
        if(isset($checkAlive['id']))
        {
            return 'This asset is belongs to another user , cannot create assignment';
        }
        if (User::isAdmin()) {
            $request['started_date'] = Carbon::now();
            $request['created_by'] = Auth::user()->id;
            if(!isset($request['status_id']))
            {
                $request['status_id'] = 1;
            }
            else
            {
                $request['is_assigned'] = 1;
            }
            $query = Assignment::query()->create($request);
            return $query;
        } else {
            return ["message:" => "You don't have permission"];
        }
    }
    private function filterColumn(Request $request)
    {
        if(User::isAdmin())
        {
            $requests = Assignment::userId($request)
                ->with('asset')
                ->with('user')
                ->with('createBy')
                ->assetId($request)
                ->startedDate($request)
                ->endDate($request)
                ->createBy($request)
                ->status($request);
        }
        else if(User::isStaff())
        {
            $requests = Assignment::where('assignment.user_id',Auth::user()->id)
                ->userId($request)
                ->with('asset')
                ->with('user')
                ->with('createBy')
                ->assetId($request)
                ->startedDate($request)
                ->endDate($request)
                ->createBy($request)
                ->status($request);
        }
        return $requests;
    }
    public function updateAssignmentById($id, $request)
    {
        $query = Assignment::query();
        if(isset($request['new_asset']))
        {
            $q = Assignment::query()
                ->where('assignment.id',$id)
                ->first();
            if($q['is_assigned'] === 1 || $q['status_id'] !== 1 && $q['is_assigned'] === 0)
            {
                return 0;
            }
            unset($request['new_asset']);
            if($request['status_id'] === 2)
            {
                $request['is_assigned'] = 1;
            }
            else
            {
                $request['end_date'] = Carbon::now('Asia/Ho_Chi_Minh');
            }
            return $this->update($id, $query, $request);
        }
        else
        {
            if($request['is_assigned'] == 1)
            {
                if($request['status_id'] == 2)
                {
                    $request['end_date'] = Carbon::now();
                    $request['status_id'] = 4;
                    $request['is_assigned'] = 0;
                }
                else if($request['status_id'] == 3)
                {
                    $request['status_id'] = 2;
                }
                return $this->update($id,$query,$request);
            }
            else
            {
                $q = Assignment::query()
                    ->where('assignment.id',$id)
                    ->first();
                if($q['status_id'] === 1)
                {
                    return 0;
                }
                unset($request['is_assigned']);
                return $this->update($id, $query, $request);
            }
        }
    }
    public function getAssignmentById($request,$id)
    {
        if (User::isAdmin()) {
            $query = Assignment::where('assignment.asset_id' , $id)
                ->with('user')
                ->with('createBy')
                ->with('status')
                ->userId($request)
                ->startedDate($request)
                ->endDate($request)
                ->createBy($request)
                ->status($request);
        } elseif (User::isStaff()) {
            $query = Assignment::where(['assignment.asset_id' => $id, 'assignment.user_id' => Auth::user()->id])
                ->with('user')
                ->with('createBy')
                ->with('status')
                ->startedDate($request)
                ->endDate($request)
                ->createBy($request)
                ->status($request);
            if ($query->isEmpty()) {
                    return 1;
            }
        }
        $data = $this->mapping($request, $this->mapping);
        $result = $this->getSort($query, $data);
        if($data['column'] !== 'status_id')
        {
            $result->orderBy('status_id');
        }
        return $result->paginate($data['limit']);
    }

    public function deleteById($id)
    {
        $q = Assignment::query()
            ->where('assignment.id',$id)
            ->first();
        if($q['is_assigned'] === 1)
        {
            return 0;
        }
        $query = Assignment::query();
        $request = [
            'status_id' => 4,
            'end_date' => Carbon::now('Asia/Ho_Chi_Minh')
        ];
        $this->update($id, $query, $request);
        return $this->delete($id,$query);
    }

}

<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Requests;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestService extends BaseService
{
    public function model(){
        return Requests::class;
    }
    public function __construct()
    {
        $this->mapping = collect([
            'Assignment' => 'assignment_id',
            'Status' => 'status',
            'User' => 'user_id',
            'RequestedDate' => 'requested_date',
            'ResponseDate' => 'response_date',
            'CreatedBy' => 'created_by',
            'AssetCategory' => 'asset_category_id',
        ]);
    }
    public function getAllRequests(Request $request)
    {
        $query = $this->filterColumn($request);
        $data = $this->mapping($request, $this->mapping);
        $result = $this->getSort($query, $data);
        if($data['column'] === 'status')
        {
            $result->orderBy('assignment_id');
        }
        else
        {
            $result->orderBy('status');
        }
        if (User::isAdmin()) {
            return $result->paginate($data['limit']);
        } elseif (User::isStaff()) {
            $id_user = Auth::user()->id;
            $result = $result->where(['user_id' => $id_user]);
            return $result->paginate($data['limit']);
        }
    }


    public function getRequestById($id)
    {
        $query = Requests::query();
        $request = Requests::where('id', $id)->first();
        if (empty($request)) {
            return "This request does not exist";
        } else {
            if (User::isAdmin()) {
                return $this->getById($id, $query);
            } elseif (User::isStaff()) {
                if ($request->user_id == Auth::user()->id) {
                    return $this->getById($id, $query);
                } else {
                    return "You are not allowed";
                }
            }
        }
    }
    private function filterColumn(Request $request)
    {
        $requests = Requests::with(['user','createBy'])
            ->with('assignment.asset')
            ->with('assignment')
            ->with('category')
            ->userId($request)
            ->assignment($request)
            ->assetCategory($request)
            ->assetCode($request)
            ->assetName($request)
            ->return($request)
            ->requestedDate($request)
            ->responseDate($request)
            ->createBy($request)
            ->status($request);
        return $requests;
    }
    public function createRequest($request)
    {
        $request['requested_date'] = Carbon::now('Asia/Ho_Chi_Minh');
        $query = Requests::query();
        $list = [];
        $is_returned = $request['is_returned'];
        unset($request['is_returned']);
        if($is_returned == 1)
        {
            $checkAlive = Requests::where('request.assignment_id',$request['assignment_id'])
                ->where('request.status',1)
                ->first();
            if(isset($checkAlive['id']))
            {
                return 0;
            }
            array_push($list,$this->create($query,$request));
        }
        else
        {
            $list_category = $request['asset_category_id'];
            unset($request['asset_category_id']);
            foreach($list_category as $id)
            {
                $base_request = $request;
                $base_request['asset_category_id'] = $id;
                array_push($list,$this->create($query,$base_request));
            }
        }
        return $list;
    }
    public function getRequestAssignmentById($id)
    {
        return Requests::query()
        ->where(['assignment_id' => $id , 'response_date' => null, 'status' => 1])
        ->with('createBy')
        ->with('status')
            ->with('user')
        ->orderBy('requested_date','desc')
        ->first();
    }
    public function updateRequestById($id, $request)
    {
        $query = Requests::query();
        $request['response_date'] = Carbon::now();
        return $this->update($id, $query, $request);
    }

    public function deleteRequestById($id)
    {
        $q = Requests::query()
            ->where('request.id',$id)
            ->first();
        if($q['status'] !== 1)
        {
            return 0;
        }
        if($q['assignment_id'] !== null)
        {
            $queryAssignment = Assignment::query();
            $this->update($q['assignment_id'],$queryAssignment,['status_id'=> 2]);
        }
        $query = Requests::query();
        return $this->delete($id,$query);
    }
}

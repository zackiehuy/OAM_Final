<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function model(){
        return User::class;
    }

    public function __construct()
    {
        $this->mapping = collect([
            'UserName' => 'name',
            'UserLocation' => 'location_id',
            'UserGender' => 'gender',
            'UserEmail' => 'email',
            'UserId' => 'id',
            'UserType' => 'is_admin'
        ]);
    }

    public function getUsers()
    {
        $query = User::query()
                ->with('userLocation');
        return $this->getAll($query);
    }

    public function getAllUsers(Request $request)
    {
        $query = $this->filterColumn($request);
        $data = $this->mapping($request, $this->mapping);
        $result = $this->getSort($query, $data);
        if($data['column'] !== 'is_admin')
        {
            $result->orderBy('is_admin');
        }
        return $result->paginate($data['limit']);
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return [
                $statusCode = 404,
                $message = "This user doesn't exist"
            ];
        }
        $query = User::with('userLocation')->get();
        return $this->getById($id, $query);
    }

    public function deleteUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return "This user doesn't exist";
        }
        $check = Assignment::where('user_id', $id)
            ->where('status_id', 1)
            ->orwhere('status_id', 2)
            ->get();
        if ($check->count() <> 0) {
            $list = [];
            $list['assignments'] = [];
            $list['requests'] = [];
            foreach($check as $keys => $value)
            {
                if($value['is_assigned'] === 0)
                {
                    $assignment = [
                        'assignment_id' => $value['id'],
                        'status_id' => 2,
                        'is_assigned' => 1,
                    ];
                    array_push($list['assignments'],$assignment);
                }
                else if($value['is_assigned'] === 1)
                {
                    $request = [
                        'user_id' => $id,
                        'assignment_id' => $value['id'],
                        'created_by' => Auth::user()->id,
                        'is_returned' => 1,
                    ];
                    array_push($list['requests'],$request);

                    $assignment = [
                        'assignment_id' => $value['id'],
                        'status_id' => 1,
                        'is_assigned' => 0,
                    ];
                    array_push($list['assignments'],$assignment);
                }
            }
            return $list;
        }
        else
        {
            $query = User::query();
            $this->delete($id, $query);
            return "Delete user success";
        }
    }

    public function historyById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return [
                $statusCode = 404,
                $message = "This user doesn't exist"
            ];
        }
        $assign = Assignment::where('user_id', $id)
            ->join('asset', 'asset.id', '=', 'assignment.asset_id')
            ->select('assignment.*', 'asset.name')
            ->get();
        return $assign;
    }

    private function filterColumn(Request $request)
    {
        $users = User::with('userLocation')
            ->name($request)
            ->staffCode($request)
            ->email($request)
            ->location($request)
            ->dateOfBirth($request)
            ->joinedDate($request)
            ->gender($request)
            ->type($request);
        return $users;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserById($id, $request)
    {
        $query = User::query();
        return $this->update($id, $query, $request);
    }

    public function createUser($request)
    {
        $query = User::query();
        $request['staff_code'] = $this->generateCode();
        $request['password'] = Hash::make($request['password']);
        return $this->create($query, $request);
    }

    public function generateCode()
    {
        $id = User::select('id')->max('id') + 1;
        $result = strval($id);
        $id = 'NT' . str_pad($result, 4, 0, STR_PAD_LEFT);
        return $id;
    }
}

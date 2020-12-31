<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Services\AssignmentService;
use App\Services\LocationService;
use App\Services\RequestService;
use App\Services\UserService;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userService;
    protected $locationService;
    protected $assignmentService;
    protected $requestService;

    public function __construct
    (
        UserService $userService,
        LocationService $locationService,
        AssignmentService $assignmentService,
        RequestService $requestService
    )
    {
        $this->userService = $userService;
        $this->locationService = $locationService;
        $this->assignmentService = $assignmentService;
        $this->requestService = $requestService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers($request);
        return response()->json($users);
    }

    public function destroy($id)
    {
        $data = $this->userService->deleteUserById($id);
        if($data === "This user doesn't exist")
        {
            return "This user doesn't exist";
        }
        else if($data == "Delete user success")
        {
            return "Delete user success";
        }
        else
        {
            $list = [];
            foreach($data['assignments'] as $keys => $value)
            {
                $assignment_id = $value['assignment_id'];
                unset($value['assignment_id']);
                $d = $this->assignmentService->updateAssignmentById($assignment_id, $value);
                array_push($list,$d);
            }
            foreach($data['requests'] as $keys => $value)
            {
                $d = $this->requestService->createRequest($value);
                array_push($list,$d);
            }
            return $list;
        }
    }

    public function show(int $id)
    {
        $user = $this->userService->getUserById($id);
        return response()->json($user);
    }
    public function history($id)
    {
        $data = $this->userService->historyById($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $request->validated();
        $data = $this->userService->updateUserById($id, $request->input());
        return response()->json($data);
    }
    /*
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */

    public function store(UserRequest $request)
    {
        $request->validated();
        $data = $this->userService->createUser($request->input());
        return response()->json($data);
    }
}

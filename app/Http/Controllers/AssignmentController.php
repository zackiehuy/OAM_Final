<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestsRequest;
use App\Services\AssetService;
use App\Services\AssignmentHistoryService;
use App\Services\AssignmentService;
use App\Services\RequestService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $assignmentService;
    protected $assetService;
    protected $userService;
    protected $requestService;

    public function __construct(
        AssignmentService $assignmentService,
        AssetService $assetService,
        UserService $userService,
        RequestService $requestService
    )
    {
        $this->assignmentService = $assignmentService;
        $this->assetService = $assetService;
        $this->userService = $userService;
        $this->requestService = $requestService;
    }
    public function index(Request $request)
    {
            $data = $this->assignmentService->getAllAssignments($request);
            return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $this->assignmentService->createAssignment($request->input());
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $data = $this->assignmentService->updateAssignmentById($id, $request->input());

        return $data;
    }

    public function create()
    {
        $assets = $this->assetService->getAssets();
        $users = $this->userService->getUsers();
        $list = [
            "assets" => $assets,
            "users" => $users,
        ];
        return $list;
    }

    public function history($id)
    {
        return $this->requestService->getRequestAssignmentById($id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->assignmentService->deleteById($id);
    }

}

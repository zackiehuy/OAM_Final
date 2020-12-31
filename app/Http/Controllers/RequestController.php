<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestsRequest;
use App\Models\Asset;
use App\Models\Requests;
use App\Services\AssetCategoryService;
use App\Services\RequestService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $requestService;
    protected $userService;
    protected $assetCategoryService;
    public function __construct
    (
        RequestService $requestService,
        AssetCategoryService $assetCategoryService,
        UserService $userService
    )
    {
        $this->requestService = $requestService;
        $this->userService = $userService;
        $this->assetCategoryService = $assetCategoryService;
    }

    public function index(Request $request)
    {
        $data = $this->requestService->getAllRequests($request);
        return response()->json($data);
    }


    public function create()
    {
        $users = $this->userService->getUsers();
        return $users;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\RequestsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestsRequest $request)
    {
            $request->validated();
            $data = $this->requestService->createRequest($request->input());
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
        $data = $this->requestService->getRequestById($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $data = $this->requestService->updateRequestById($id, $request->input());
        if (!empty($request['status']) && !empty($request['assignment_id'])) {
            $assignment = Requests::with('assignment')->where('id', $id)->first();
            $assignment->assignment->update(['status_id' => $request['status']]);
        }
        return response()->json($data);
    }

    /**
     * Remove the specified reapisource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->requestService->deleteRequestById($id);
    }
}

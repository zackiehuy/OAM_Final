<?php

namespace App\Http\Controllers;

use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Requests\AssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Assignment;
use App\Services\AssetCategoryService;
use App\Services\AssetDetailService;
use App\Services\AssetService;
use App\Services\AssignmentService;
use App\Services\CategorySpecificationService;
use App\Services\SpecificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\LocationService;
use Illuminate\Http\Response;

class AssetController extends Controller
{
    private $id;
    protected $assetService;
    protected $locationService;
    protected $assetCategoryService;
    protected $optionSpecificationService;
    protected $requireSpecificationService;
    protected $assetDetailService;
    protected $assignmentService;

    public function __construct(
        LocationService $locationService,
        AssetCategoryService $assetCategoryService,
        SpecificationService $optionSpecificationService,
        CategorySpecificationService $requireSpecificationService,
        AssetService $assetService,
        AssetDetailService $assetDetailService,
        AssignmentService $assignmentService
    ) {
        $this->locationService = $locationService;
        $this->assetCategoryService = $assetCategoryService;
        $this->optionSpecificationService = $optionSpecificationService;
        $this->requireSpecificationService = $requireSpecificationService;
        $this->assetService = $assetService;
        $this->assetDetailService = $assetDetailService;
        $this->assignmentService = $assignmentService;
    }

    public function index(Request $request)
    {
        $data = $this->assetService->getAllAssets($request);
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listCategory = $this->assetCategoryService->getAllCategory();
        $listRequireSpecification = $this->requireSpecificationService->getRequireSpecification();
        $rSpecification = [];
        for ($i = 0; $i < count($listCategory); $i++) {
            array_push($rSpecification, $listCategory[$i]->id = []);
        }
        for ($i = 0; $i < count($listRequireSpecification); $i++) {
            array_push($rSpecification[$listRequireSpecification[$i]->asset_category_id - 1], $listRequireSpecification[$i]);
        }
        return $rSpecification;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AssetRequest $request)
    {
        if($request['installed_date'] == '')
        {
            $request['installed_date'] = Carbon::now();
        }
        $asset = [
            "name" => $request->input('name'),
            "location_id" => $request->input('location_id'),
            "asset_category_id" => $request->input('asset_category_id'),
            "installed_date" => $request->input('installed_date'),
        ];
        $asset['asset_code'] = $this->assetService->checkAssetCode($request['asset_code']);
        $newAsset = $this->assetService->createAsset($asset);
        if($asset['asset_code'] == '')
        {
            $this->assetService->createAssetCode($newAsset->id);
        }
        $this->assetDetailService->listCreateDetailAsset($request, $newAsset->id);
        $updateAsset = $this->assetService->getAssetById($newAsset->id);
        return $updateAsset;
    }

    /**
     * Display the specified resource.
     *
     * @param Asset $asset
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $asset = $this->assetService->getAssetById($id);
        if ($asset === null) {
            return "Don't have this id";
        }
        $assetDetail = $this->assetDetailService->getAssetDetailById($id);
        foreach($assetDetail as $keys => $value)
        {
            $value['default_value'] = $value['value'];
        }
        $listCategory = $this->assetCategoryService->getAllCategory();
        $listRequireSpecification = $this->requireSpecificationService->getRequireSpecification();
        $rSpecification = [];
        for ($i = 0; $i < count($listCategory); $i++) {
            array_push($rSpecification, $listCategory[$i]->id = []);
        }
        for ($i = 0; $i < count($listRequireSpecification); $i++) {
            array_push($rSpecification[$listRequireSpecification[$i]->asset_category_id - 1], $listRequireSpecification[$i]);
        }
        foreach($rSpecification as $keys => $value)
        {
            foreach($value as $column => $val)
            {
                if($val->asset_category_id == $asset['asset_category_id'])
                {
                    foreach($assetDetail as $keyAssetDetail => $valueAssetDetail)
                    {
                        if($val->specification_id == $valueAssetDetail->specification_id)
                        {
                            $val->default_value = $valueAssetDetail->value;
                        }
                    }
                }
            }
        }
        $list = [
            "asset" => $asset,
            "require_specification" => $rSpecification,
        ];
        return $list;
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\AssetRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAssetRequest $request, int $id)
    {
        $asset = $this->assetService->updateAsset($id, $request->input());
        $asset_detail = $this->assetDetailService->listUpdateDetailAsset($request, $id);
        $list = [
            'asset' => $asset,
            'asset_detail'=> $asset_detail,
        ];
        return response()->json($list);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $data = $this->assetService->deleteByID($id);
        return response()->json($data, $data['statusCode']);
    }
    public function history(Request $request,int $id)
    {
        $asset = $this->assetService->historyAsset($id);
        if($asset === 1)
        {
            return "This asset is not available";
        }
        $assignment = $this->assignmentService->getAssignmentById($request,$id);;
        if($assignment === 1)
        {
            return "This asset is not belong to you";
        }
        $list = [
          'asset' => $asset,
          'assignment' => $assignment
        ];
        return $list;
    }
}

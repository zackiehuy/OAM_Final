<?php

namespace App\Http\Controllers;

use App\Services\AssetCategoryService;
use App\Services\CategorySpecificationService;
use App\Services\LocationService;
use App\Services\StatusService;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    protected $statusService;
    protected $locationService;
    protected $assetCategoryService;
    protected $categorySpecificationService;

    public function __construct(
        StatusService $statusService,
        LocationService $locationService,
        AssetCategoryService $assetCategoryService,
        CategorySpecificationService $categorySpecificationService
    )
    {
        $this->assetCategoryService = $assetCategoryService;
        $this->locationService = $locationService;
        $this->statusService = $statusService;
        $this->categorySpecificationService = $categorySpecificationService;
    }

    public function index()
    {
        $assetCategories = $this->assetCategoryService->getAllCategory();
        $locations = $this->locationService->getAllLocation();
        $statuses = $this->statusService->getAllStatus();
        $categorySpecifications = $this->categorySpecificationService->getRequireSpecification();
        $list = [
          'asset_categories' => $assetCategories,
          'locations' => $locations,
          'statuses' => $statuses,
          'category_specifications' => $categorySpecifications,
        ];
        return $list;
    }
}

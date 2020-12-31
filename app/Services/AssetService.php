<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AssetService extends BaseService
{
    public function model(){
        return Asset::class;
    }
    public function __construct()
    {
        $this->mapping = collect([
            'Id' => 'id',
            'AssetName' => 'name',
            'AssetLocation' => 'location_id',
            'AssetCategory' => 'asset_category_id',
            'AssetCode' => 'asset_code',
            'AssetInstalledDate' => 'installed_date'
        ]);
    }

    public function checkAssetCode($value)
    {
        if($value != null)
        {
            $check = Asset::query()
                ->where('asset_code','=',$value)
                ->first();
            if($check == null)
            {
                return $value;
            }
            else
            {
                return '';
            }
        }
        else
        {
            return '';
        }
    }
    public function getAllAssets(Request $request)
    {
        $query = $this->filterColumn($request);
        $data = $this->mapping($request, $this->mapping);
        if($data['column'] === 'Status')
        {
            $result = $query->orderBy('assignment',$data['sort']);
        }
        else
        {
            $result = $this->getSort($query, $data);
        }
        if (User::isStaff()) {
            $id_user = Auth::user()->id;
            $result->whereHas('assignment', function ($query) use ($id_user) {
                return $query->where('user_id', '=', $id_user);
            });
        }
        return $result->paginate($data['limit']);
    }

    public function getAssets()
    {
        $query = Asset::query()
            ->with('assetLocation')
            ->with('assetDetail')
            ->whereNotExists(function ($que) {
                $que->select(DB::raw(1))
                    ->from('assignment')
                    ->whereColumn('assignment.asset_id','asset.id')
                    ->where('assignment.status_id',1);
            })->whereNotExists(function ($que) {
                $que->select(DB::raw(1))
                    ->from('assignment')
                    ->whereColumn('assignment.asset_id','asset.id')
                    ->where('assignment.status_id',2);
            });
        return $this->getAll($query);
    }

    private function filterColumn(Request $request)
    {
        $result = Asset::with('assetDetail.specification')
            ->with('assignment')
            ->with('assetCategory')
            ->with('assetLocation')
            ->with('assignment.userAssignment')
            ->with('assetCreateBy')
            ->assetCode($request)
            ->name($request)
            ->location($request)
            ->category($request)
            ->install($request)
            ->status($request)
            ->createBy($request)
            ->spec($request);
        return $result;
    }

    public function getAsset(int $id)
    {
        $asset = new Asset();
        $statusCode = 404;
        $data = null;
        $asset = $asset->with('assignment.assignmentHistory')
            ->with('assetDetail')
            ->with('assignment.userAssignment')
            ->find($id);
        if ($asset) {
            $statusCode = 200;
            $data = $asset;
        }

        return [
            'statusCode' => $statusCode,
            'data' => $data
        ];
    }

    public function getAssetById($id)
    {
        $query = Asset::query();
        return $this->getById($id, $query);
    }

    public function getAssetByAssetCode($assetCode)
    {
        return Asset::query()
                    ->where('asset_code',$assetCode)
                    ->first();
    }

    public function createAsset($request)
    {
        $query = Asset::query();
        return $this->create($query, $request);
    }

    public function updateAsset($id, $data)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            $statusCode = 404;
            $message = "Asset not found!";
        } elseif ($asset->assignment()->exists()) {
            $statusCode = 409;
            $message = "Cannot update because this asset is currently assigned!";
        } else {
            $asset->update($data);
            $statusCode = 200;
            $message = "Update success!";
        }
        return [
            'statusCode' => $statusCode,
            'message' => $message
        ];
    }

    public function deleteByID($id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            $statusCode = 404;
            $message = "Asset not found!";
        } elseif ($asset->assignment()->exists()) {
            $statusCode = 409;
            $message = "Cannot delete because this asset is currently assigned!";
        } else {
            $asset->delete();
            $statusCode = 200;
            $message = "Delete success!";
        }
        return [
            'statusCode' => $statusCode,
            'message' => $message
        ];
    }

    public function createAssetCode($newInsertId)
    {
        $codeFirstCharacter = 'A';
        $query = Asset::query();
        return $this->createCode($query, $codeFirstCharacter, $newInsertId);
    }

    public function historyAsset($id)
    {
        $asset = Asset::where('asset.id', $id)
            ->with('assetLocation')
            ->with('assetCategory')
            ->with('assetUser')
            ->get();
        if ($asset->isEmpty()) {
            return 1;
        }
        return $asset[0];
    }
}

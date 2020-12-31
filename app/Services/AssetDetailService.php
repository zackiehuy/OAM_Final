<?php


namespace App\Services;


use App\Models\AssetDetail;

class AssetDetailService extends BaseService
{
    public function model(){
        return AssetDetail::class;
    }
    public function getAssetDetailById($id)
    {
        $query = AssetDetail::query()
            ->where('asset_id',$id);
        return $this->getAll($query);
    }

    public function createAssetDetail($request)
    {
        $query = AssetDetail::query();
        return $this->create($query,$request);
    }

    public function updateAssetDetailById($id,$request)
    {
        return AssetDetail::query()
                    ->where(['asset_id' => $id['asset_id'],'specification_id' => $id['specification_id']])
                    ->first()
                    ->update($request);
    }

    public function listCreateDetailAsset($request, $newInsertId)
    {
        $list = [];
        foreach ($request->all() as $keys => $value) {
            if ($keys == "specifications") {
                foreach($value as $id => $specificationValue)
                {
                    $assetDetail = [
                        "specification_id" => $id,
                        "asset_id" => $newInsertId,
                        "value" => $specificationValue
                    ];
                array_push($list, $this->createAssetDetail($assetDetail));
                }
            }
        }
        return $list;
    }

    public function listUpdateDetailAsset($request ,$idAsset)
    {
        $list = [];
        if($request->asset_category_id == $request->old_category)
        {
            foreach ($request->all() as $keys => $value)
            {
                if ($keys == "specifications") {
                    foreach($value as $id => $specification)
                    {
                        $id = [
                            'asset_id' => $idAsset,
                            'specification_id' => $id,
                        ];
                        $assetDetailValue = [
                            "value" => $specification
                        ];
                        array_push($list, $this->updateAssetDetailById($id,$assetDetailValue));
                    }
                }
            }
        }
        else
        {
            AssetDetail::where('asset_id',$idAsset)
                ->delete();
            foreach ($request->all() as $keys => $value)
            {
                if ($keys == "specifications") {
                    foreach($value as $id => $specification)
                    {
                        $assetDetail = [
                            "specification_id" => $id,
                            "asset_id" => $idAsset,
                            "value" => $specification
                        ];
                        array_push($list, $this->createAssetDetail($assetDetail));
                    }
                }
            }
        }
        return $list;
    }
}

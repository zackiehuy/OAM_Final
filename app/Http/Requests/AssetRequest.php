<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
//            'asset_code' => 'checkAssetCodeExisted',
            'name' => 'required',
            'location_id' => 'required|integer|checkExisted',
            'asset_category_id' => 'required|integer|checkCateExisted',
            'installed_date' => 'before_or_equal:today'
        ];
    }
    public function messages()
    {
        return parent::messages();
    }
    public function attributes()
    {
        return [
            'name' => 'Asset Name',
            'location_id' => 'Asset Location',
            'asset_category_id' => 'Asset Category',
            'asset_code' => 'Asset Code',
            'installed_date' => 'Installed Date'
        ];
    }
}

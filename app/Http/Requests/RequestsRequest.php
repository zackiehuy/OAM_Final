<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RequestsRequest extends FormRequest
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
        if (User::isStaff()) {
            return [
                'user_id' => 'required|integer|min:1',
                'assignment_id' => 'sometimes|integer|min:1|checkAssignExisted',
                'asset_category_id' => 'sometimes|required|array|min:1|checkCateExisted|'

            ];
        } elseif (User::isAdmin()) {
            return [
                'user_id' => 'required|integer|min:1',
                'assignment_id' => 'nullable|integer|min:1',
            ];
        }
    }
    public function attributes()
    {
        return [
            'user_id' => 'User',
            'assignment_id' => 'Assignment',
            'asset_category_id' => 'Asset Category',
            'requested_date' => 'Request Date',
            'response_date' => 'Response Date',
            'create_by' => 'CreatedBy',
            'status' => 'Status'
        ];
    }
}

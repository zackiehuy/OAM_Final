<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        //$id = $this->request->get('id');
        return [
            'name' => 'sometimes|required',
            'date_of_birth' => 'sometimes|required|date|checkAge',
            'joined_date' => 'sometimes|date|before_or_equal:today|after:date_of_birth|checkDate',
            'location_id' => 'sometimes|required|checkExisted|',
            'gender' => 'sometimes|required|boolean|',
        ];
    }
    public function messages()
    {
        return parent::messages();
    }
    public function attributes()
    {
        return [
            'name' => 'User Name',
            'Staff_code' => 'User_code',
            'email' => 'User_email',
            'joined_date' => 'User_joined_date',
        ];
    }
}

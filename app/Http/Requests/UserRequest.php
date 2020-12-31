<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'date_of_birth' => 'required|date|checkAge',
            'joined_date' => 'date|before_or_equal:today|after:date_of_birth|checkDate',
            'location_id' => 'required|checkExisted|',
            'gender' => 'required|boolean|',
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

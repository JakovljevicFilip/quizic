<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
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
            'username'=>'required|regex:/^([A-Za-z0-9_])+$/|min:8|max:20|unique:users',
            'email'=>'required|email|unique:users',
            'password'=>'required|regex:/^([A-Za-z0-9_])+$/|min:8|max:20|confirmed',
        ];
    }
}

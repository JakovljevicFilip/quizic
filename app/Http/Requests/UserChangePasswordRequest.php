<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\VerifyHashRule;

class UserChangePasswordRequest extends FormRequest
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
            'password_compare' => ['required', 'min:6', new VerifyHashRule('id', 'password')],
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages(){
        return [

        ];
    }
}

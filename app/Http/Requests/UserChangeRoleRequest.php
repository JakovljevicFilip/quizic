<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlteringYourselfRule;
use App\User;

class UserChangeRoleRequest extends FormRequest
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
            'id' => ['required', 'exists:users,id', new AlteringYourselfRule()],
            'role' => 'required|in:1,2',
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'Request error: Invalid value.',
            'role.in' => 'Request error: Invalid value.',
        ];
    }
}

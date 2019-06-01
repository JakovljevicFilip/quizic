<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ExistsOnUpdateRule;
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
            // ExistsOnUpdate - ENSURES THAT THERE STILL EXISTS A COLUMN WITH CERTAIN VALUE
            'user' => [new ExistsOnUpdateRule(new User, 'role', 2)],
            'user.id' => 'required|exists:users,id',
            'user.role' => 'required|in:1,2',
        ];
    }

    public function messages()
    {
        return [
            'user.id.exists' => 'Request error: Invalid value.',
            'user.role.in' => 'Request error: Invalid value.',
        ];
    }
}

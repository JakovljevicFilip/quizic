<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ExistsOnUpdateRule;
use App\User;

class UserUpdateRequest extends FormRequest
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
            'users' => ['required', 'array', new ExistsOnUpdateRule(new User, 'role', 2)],
            'users.*.id' => 'required|exists:users,id',
            'users.*.role' => 'required|in:1,2',
        ];
    }

    public function messages()
    {
        return [
            'users.*.id.exists' => 'Invalid id for :attribute',
            'users.*.role.in' => 'Invalid role for :attribute',
        ];
    }
}

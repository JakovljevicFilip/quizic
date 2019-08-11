<?php

namespace Quizic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DifficultyStoreRequest extends FormRequest
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
            'text' => 'required|unique:difficulties'
        ];
    }

    public function messages()
    {
        return [
            'text.unique' => 'This difficulty already exists.',
        ];
    }
}

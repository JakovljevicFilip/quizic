<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

// A CUSTOM RULE I'VE MADE TO CHECK FOR CERTAIN NUMBER OF CERTAIN VALUES
use App\Rules\NumberOfValues;

class QuestionUpdateRequest extends FormRequest
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
            'id' => 'required|numeric|exists:questions,id',
            'text'=>'required|unique:questions,text,'.$this->get('id'),
            'difficulty_id'=>'required|exists:difficulties,id',
            'answers'=>['required','array',new NumberOfValues('status',[
                    // EXPECTING 3 ANSWERS WITH STATUS 0
                    '0'=>3,
                    // EXPECTING 1 ANSWER WITH STATUS 1
                    '1'=>1
                ]
            )],
            'answers.*.text'=>'required',
            'answers.*.status'=>'required|boolean'
        ];
    }
}

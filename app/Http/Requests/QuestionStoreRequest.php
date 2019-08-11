<?php

namespace Quizic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// A CUSTOM RULE I'VE MADE TO CHECK FOR CERTAIN NUMBER OF CERTAIN VALUES
use Quizic\Rules\NumberOfValuesRule;

class QuestionStoreRequest extends FormRequest
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
            'text'=>'required|unique:questions',
            'difficulty_id'=>'required|exists:difficulties,id',
            'answers'=>['required','array',new NumberOfValuesRule('status',[
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

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class ExistsOnUpdateRule implements Rule
{
    private $model;
    private $column;
    private $value;
    private $passed;
    private $notInArray;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model, $column, $value)
    {
        // SET MODEL
        $this->model = $model;
        // SET COLUMN
        $this->column = $column;
        // SET VALUE
        $this->value = $value;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // CHECK IF UPDATE REQUEST CONTAINS VALUE SPECIFIED BY RULE
        $this->requestCheckValues($value);

        // VALIDATION DIDN'T PASS ALREADY
        if(! $this->passed){
            // CHECK IF DATABASE CONTAINS VALUE SPECIFIED BY RULE
            $this->databaseCheckValues($value);
        }

        return $this->passed;
    }

    private function requestCheckValues($value){
        // ITTERATE THROUGH ARRAY PASSED FROM THE FRONT-END
        for($i = 0; $i < sizeof($value); $i++){
            // ITTERATE THROUGH RULES
            $this->requestCheckRules($value[$i]);
        }
    }

    private function requestCheckRules($data){
        // REQUIRED VALUE IS BEING SET THROUGH REQUEST
        if($data[$this->column] === $this->value){
            // VALDIATION PASSED
            $this->passed = true;
        }
    }

    private function databaseCheckValues($value){
        // SET ARRAY USED FOR NOT IN FUNCTIONALITY
        $this->setNotInArray($value);

        // IF HIGHER THAN 0 THERE ARE COLUMNS WITH THE REQUIRED VALUE REMAINING IN THE DB
        $this->passed = $this->model->where($this->column, $this->value)->whereNotIn('id', $this->notInArray)->count() > 0;
    }

    private function setNotInArray($value){
        // ITTERATE THROUGH ARRAY PASSED FROM THE FRONT-END
        for($i = 0; $i < sizeof($value); $i++){
            // ARRAY OF IDS THAT ARE NOT TO BE TAKEN INTO CONSIDERATION
            $this->notInArray[] = $value[$i]['id'];
        }
    }



    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Database needs at least one column with the '".$this->column."' value of ".$this->value;
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class ExistsOnUpdateWithRequestRule implements Rule
{
    // MODEL THAT IS BEING UPDATED
    private $model;
    // COLUMN THAT IS BEING UPDATED
    private $column;
    // VALUE THAT IS BEING UPDATED
    private $value;
    // VALIDATION MESSAGE
    private $message;

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
    public function passes($attribute, $data)
    {
        // NECESSARY DATA IS PROVIDED
        if($this->checkKeys($data)){
            // CHECK REQUEST AND REMAINING ROWS IN THE DB FOR REQUIRED COLUMN VALUE
            return $this->requestCheckRule($data) || $this->databaseCheckRule($data);
        }
        else{
            $this->message = 'There are missing parameters.';
        }
        // NECESSARY DATA IS NOT PROVIDED
        return false;
    }

    private function checkKeys($data){
        // REQUESTED COLUMN AND ID VALUES ARE BOTH PROVIDED
        return array_key_exists($this->column, $data) && array_key_exists('id', $data);
    }

    private function requestCheckRule($data){
        // REQUIRED COLUMN VALUE IS BEING SET THROUGH REQUEST
        return $data[$this->column] === $this->value;
    }

    private function databaseCheckRule($data){
        // THERE ARE OTHER ROWS IN THE DB WITH THE REQUESTED COLUMN
        if($this->model->where($this->column, $this->value)->whereNotIn('id', [$data['id']])->count() > 0){
            // VALDIATION PASSES
            return true;
        }
        else{
            // SET MESSAGE
            $this->message = "Database needs at least one column with the '".$this->column."' value of ".$this->value;
            // VALDIATION FAILS
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}

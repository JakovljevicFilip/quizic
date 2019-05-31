<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class ExistsOnUpdateRule implements Rule
{
    // MODEL THAT IS BEING UPDATED
    private $model;
    // COLUMN THAT IS BEING UPDATED
    private $column;
    // VALUE THAT IS BEING UPDATED
    private $value;

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
        // CHECK REQUEST AND REMAINING COLUMNS IN THE DB FOR REQUIRED COLUMN VALUE
        return $this->requestCheckRule($data) || $this->databaseCheckRule($data);
    }

    private function requestCheckRule($data){
        // REQUIRED COLUMN VALUE IS BEING SET THROUGH REQUEST
        return $data[$this->column] === $this->value;
    }

    private function databaseCheckRule($data){
        // THERE ARE OTHER USERS IN THE DB WITH THE REQUESTED COLUMN
        return $this->model->where($this->column, $this->value)->whereNotIn('id', [$data['id']])->count() > 0;
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

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistsOnUpdateWithoutRequestRule implements Rule
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
        // GET REQUEST COLUMN VALUE
        $requestColumnValue = $this->getRequestColumnValue($data);
        // COLUMN VALUE IS RESTRICTED
        if($requestColumnValue === $this->value){
            // THERE ARE OTHER ROWS IN THE DB WITH THE REQUESTED COLUMN
            return $this->model->where($this->column, $this->value)->whereNotIn('id', [$data])->count() > 0;
        }
        // COLUMN VALUE IS SOMETHING ELSE
        return true;
    }

    private function getRequestColumnValue($data){
        return $this->model->where('id', $data)->first()[$this->column];
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

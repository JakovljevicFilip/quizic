<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class VerifyHashRule implements Rule
{
    private $model;
    private $primaryKey;
    private $primaryKeyValue;
    private $hashColumn;
    private $hashColumnValue;

    private $id;
    private $compare;

    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model, $primaryKey, $column)
    {
        // MODEL THAT IS BEING CHECKED
        $this->model = $model;
        // PRIMARY KEY USED FOR ROW IDENTIFICATION
        $this->primaryKey = $primaryKey;
        // COLUMN THAT IS BEING CHECKED
        $this->hashColumn = $column;
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
        // THERE IS A KEY MISSING FROM THE FRONT-END
        if(! $this->checkKeys($value)){
            return false;
        }

        // SET VALUES FOR FURTHER USE
        $this->setValues($value);

        // HASH VERIFICATION FAILED
        if(! $this->verifyHash()){
            $this->message = 'The '.$this->hashColumn.' is incorrect.';
            return false;
        }

        // HASH VERIFICATION PASSED
        return true;
    }

    private function checkKeys($array){
        // PRIMAY KEY HAS NOT BEEN PASSED
        if(! array_key_exists($this->primaryKey, $array)){
            $this->message = 'Hash identification key has not been passed properly, expecting: '.$this->primaryKey;
            return false;
        }
        // COMPARE KEY HAS NOT BEEN PASSED
        if(! array_key_exists($this->hashColumn.'_compare', $array)){
            $this->message = 'Comparison value requires sufix "_compare", expecting: '.$this->hashColumn.'_compare';
            return false;
        }
        // KEYS HAVE BEEN PASSED
        return true;
    }

    private function setValues($value){
        // USED FOR IDENTIFYING COLUMN
        $this->primaryKeyValue = $value[$this->primaryKey];
        // VALUE THAT IS USED FOR HASH VERIFICATION
        $this->hashColumnValue = $value[$this->hashColumn.'_compare'];
    }

    private function verifyHash(){
        // ROW FROM DB
        $row = $this->model->where($this->primaryKey, $this->primaryKeyValue)->firstOrFail();
        // GET HASH
        $hash = $row[$this->hashColumn];
        // VERIFY HASH
        return Hash::check($this->hashColumnValue, $hash);
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

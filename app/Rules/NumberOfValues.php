<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NumberOfValues implements Rule
{
    private $parameters;
    private $fieldName;
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($fieldName,$parameters)
    {
        $this->fieldName=$fieldName;

        $i=0;
        foreach ($parameters as $key => $value) {
            $this->parameters[$i]['key']=$key;
            $this->parameters[$i]['value']=$value;
            $this->parameters[$i]['counter']=0;
            $i++;
        }
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
        $this->attribute=$attribute;
        for($i=0;$i<count($value);$i++){
            for($j=0;$j<count($this->parameters);$j++){
                if($value[$i][$this->fieldName]===$this->parameters[$j]['key']){
                    $this->parameters[$j]['counter']++;
                }
            }
        }
        
        for($i=0;$i<count($this->parameters);$i++){
            if($this->parameters[$i]['value']!==$this->parameters[$i]['counter'])
                return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The required values for:'.$this->attribute.'->'.$this->fieldName.' have not been submited correctly.';
    }
}

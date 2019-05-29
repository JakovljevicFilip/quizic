<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NumberOfValuesRule implements Rule
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
        // NAME OF THE FILED WE'RE CHECKING
        $this->fieldName=$fieldName;

        // FOR EACH KEY=>VALUE PAIR
        $i=0;
        foreach ($parameters as $value => $number) {
            // VALUE THAT WE'D LIKE TO APPEAR
            $this->parameters[$i]['value']=$value;
            // NUMBER OF TIMES WE WANT IT TO APPEAR
            $this->parameters[$i]['number']=$number;
            // FUTURE ITTERATOR FOR NUMBER OF TIMES IT APPEARED
            $this->parameters[$i]['counter']=0;
            // GOES THROUGH THE ENTIRE ARRAY WE'VE PASSED
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
        // STORES NAME OF THE ARRAY PASSED FROM THE FRONT-END, USED LATER IN CASE ERROR OCCURS
        $this->attribute=$attribute;
        // ITTERATES THROUGH THE ARRAY PASSED FROM THE FRONT-END
        for($i=0;$i<count($value);$i++){
            // ITTERATES THROUGH THE ARRAY OF RULES
            for($j=0;$j<count($this->parameters);$j++){
                // IF KEY FROM FRONT-END MATCHES OUR KEY
                if($value[$i][$this->fieldName]===$this->parameters[$j]['value']){
                    // INCREMENT COUNTER
                    $this->parameters[$j]['counter']++;
                }
            }
        }

        // ITTERATES THROUGH THE ARRAY OF RULES
        for($i=0;$i<count($this->parameters);$i++){
            // IF NUMBER OF TIMES WE'VE WANTED SOMETHING TO APPEAR INS'T MET
            if($this->parameters[$i]['number']!==$this->parameters[$i]['counter'])
                // VALIDATION FAILED
                return false;
        }
        // VALIDATION SUCCEEDED
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message='The required values for:'.$this->attribute.'->'.$this->fieldName.' have not been submited correctly.';
    }
}

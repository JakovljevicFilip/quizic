<?php

namespace Quizic\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Quizic\User;

class VerifyHashRule implements Rule
{
    private $hashColumn;
    private $value;
    private $id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($primaryKey, $column)
    {
        // GET USER ID
        $this->id = Auth::user()->id;
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
        // HASH VERIFICATION
        return $this->verifyHash($value);
    }

    private function verifyHash($value){
        // ROW FROM DB
        $row = User::where('id', $this->id)->firstOrFail();
        // GET HASH
        $hash = $row[$this->hashColumn];
        // VERIFY HASH
        return Hash::check($value, $hash);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Current password is incorrect.';
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model
{
    protected $guarded=[];

    public function questions(){
    	return $this->hasMany(Question::class);
    }
}

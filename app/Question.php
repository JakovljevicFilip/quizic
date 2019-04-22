<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Difficulty;
use App\Answer;


class Question extends Model
{
    protected $guarded=[];

    public function difficulty(){
    	return $this->belongsTo(Difficulty::class);
    }

    public function answers(){
    	return $this->hasMany(Answer::class);
    }

}

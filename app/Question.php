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

    public function saveAnswers($answers){
        return $this->answers()->createMany($answers);
    }

    public function updateAnswers($answers){
        // DELETE PREVIOUS ANSWERS
        $this->answers()->delete();
        // STORE NEW ANSWERS
        return $this->saveAnswers($answers);
    }
}

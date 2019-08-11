<?php

namespace Quizic;

use Illuminate\Database\Eloquent\Model;
use Quizic\Difficulty;
use Quizic\Answer;


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

    public function fetchQuestions($request){
        return $this->fetchByDifficulty($request->difficulty)->paginate($request->per_page);
    }

    public function fetchByDifficulty($difficulty){
        return $this->where('difficulty_id',$difficulty)->with('answers');
    }

}

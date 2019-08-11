<?php

namespace Quizic;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded = [];

	public $timestamps=false;

    public function question(){
    	return $this->belongsTo(Question::class);
    }

    public function scopeGameAnswers($query){
        return $query->select('text','id');
    }
}

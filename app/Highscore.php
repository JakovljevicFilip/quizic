<?php

namespace Quizic;

use Illuminate\Database\Eloquent\Model;

class Highscore extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'username',
        'score',
    ];

    public function setNewHighscore($score, $username, $scoreToBeat){
        $this->deleteLowestScore($scoreToBeat);
        $this->attributes['username'] = $username;
        $this->attributes['score'] = $score;

        $this->save();
    }

    private function deleteLowestScore($scoreToBeat){
        $lowestScore = Highscore::where('score', $scoreToBeat)->orderBy('id', 'desc')->first();
        $lowestScore->delete();
    }
}

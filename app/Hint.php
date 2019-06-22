<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hint extends Model
{
    public $timestamps = false;

    public function games()
    {
        return $this->belongsToMany('App\Game');
    }
}

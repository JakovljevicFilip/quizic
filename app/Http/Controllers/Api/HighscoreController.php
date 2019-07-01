<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Highscore;

class HighscoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // GET HIGHSCORES
        $highscores = Highscore::orderBy('score', 'desc')->orderBy('id', 'asc')->get();

        // RETURN RESPONSE
        return response()->json([
            'message' => 'Highscores fetched.',
            'write' => false,
            'highscores' => $highscores,
        ],200);
    }
}

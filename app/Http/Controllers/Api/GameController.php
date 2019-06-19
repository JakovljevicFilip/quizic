<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Game;
use App\Http\Requests\GameAnswerRequest;

class GameController extends Controller
{
    public function startGame(){
        $game = new Game();
        $game = $game->startGame();

        return response()->json([
            'message'=>'Game started.',
            'write'=>false,
            'game'=>$game,
        ],200);
    }

    public function answer(GameAnswerRequest $request){
        // GET GAME
        $game = Game::where('hash', $request->answer['game_id'])->first();
        // VALIDATE GAME ANSWER
        $game = $game->validateAnswer($request->answer['id']);

        // ANSWER IS INCORRECT
        if(array_key_exists('message', $game)){
            return response()->json($game,200);
        }

        // ANSWER IS CORRECT
        return response()->json([
            'message' => 'Answer is correct.',
            'write' => false,
            'game' => $game,
        ],200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Game;
use App\Http\Requests\GameAnswerRequest;
use App\Http\Requests\GameHintRequest;


class GameController extends Controller
{
    public function startGame(){
        $game = new Game();
        return response()->json($game->startGame(),200);
    }

    public function answer(GameAnswerRequest $request){
        // GET GAME
        $game = Game::where('hash', $request->answer['game_id'])->first();
        // ANSWER IS CORRECT
        return response()->json($game->validateAnswer($request->answer['id']),200);
    }

    public function destroy(Request $request){
        $game = Game::where('hash', $request->game_id)->first();
        // GAME EXISTS
        if($game !== null){
            // DELETE
            $game->delete();
        }

        // GAME IS DELETED
        return response()->json([
            'message' => 'Game has ended.',
            'write' => false,
        ],200);
    }

    public function hint(GameHintRequest $request){
        $game = Game::where('hash', $request->hint['game_id'])->first();
        $hint = $request->hint['text'];

        // GAME IS DELETED
        return response()->json($game->$hint(),200);
    }
}

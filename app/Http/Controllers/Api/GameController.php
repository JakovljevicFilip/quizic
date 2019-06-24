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
        $response = $game->startGame();

        return response()->json($response,200);
    }

    public function answer(GameAnswerRequest $request){
        // GET GAME
        $game = Game::where('hash', $request->answer['game_id'])->first();
        // VALIDATE GAME ANSWER
        $response = $game->validateAnswer($request->answer['id']);

        // ANSWER IS CORRECT
        return response()->json($response,200);
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
        // dd($request);
        $game = Game::where('hash', $request->game_id)->first();

        $response = $game->half();

        // GAME IS DELETED
        return response()->json([
            'message' => 'Hint half has been used.',
            'write' => false,
            'incorrectAnswers' => $response,
        ],200);
    }
}

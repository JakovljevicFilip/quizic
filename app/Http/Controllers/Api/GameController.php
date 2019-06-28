<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Game;
use App\Hint;
use App\Http\Requests\GameAnswerRequest;
use App\Http\Requests\GameHintRequest;


class GameController extends Controller
{
    public function startGame(){
        // START GAME
        $game = new Game();

        // RETURN RESPONSE
        return response()->json($game->startGame(),200);
    }

    public function answer(GameAnswerRequest $request){
        // FIND GAME
        $game = $this->findGame($request->answer['game_id']);

        // RETURN RESPONSE
        return response()->json(
            // VALIDATE ANSWER
            $game->validateAnswer($request->answer['id']
        ),200);
    }

    public function destroy(Request $request){
        // FIND GAME
        $game = Game::where('hash', $request->game_id)->first();

        // GAME EXISTS
        if($game !== null){
            // DELETE
            $game->delete();
        }

        // RETURN RESPONSE
        return response()->json([
            'message' => 'Game has ended.',
            'write' => false,
        ],200);
    }

    public function hint(GameHintRequest $request){
        // GET THE GAME ID
        $game_id = $request->hint['game_id'];
        // GET THE HINT TEXT
        $hintText = $request->hint['text'];

        // FIND GAME
        $game = $this->findGame($game_id);
        // FIND HINT
        $hint = Hint::where('text', $hintText)->first();

        $response = $hint->useHint($game, $hintText);

        // RETURN HINT RESPONSE
        return response()->json($response,200);


    }

    private function findGame($game_id){
        return Game::where('hash', $game_id)->first();
    }
}

<?php

namespace Quizic\Http\Controllers\Api;

use Illuminate\Http\Request;
use Quizic\Http\Controllers\Controller;

use Validator;
use Quizic\Http\Requests\DifficultyStoreRequest;
use Quizic\Difficulty;

class DifficultyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $difficulties = Difficulty::all();
        return response()->json([
            'title' => 'Difficulty',
            'message' => 'Difficulties fetched.',
            'write' => false,
            'difficulties' => $difficulties,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DifficultyStoreRequest $request)
    {
        Difficulty::create($request->all());
        return response()->json([
            'message' => 'Difficulty added.',
            'write' => true,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Quizic\Difficulty  $difficulty
     * @return \Illuminate\Http\Response
     */
    public function show(Difficulty $difficulty)
    {
        return response()->json([
            'title' => 'Difficulty',
            'message' => 'Difficulty fetched.',
            'write' => false,
            'difficulty' => $difficulty,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Quizic\Difficulty  $difficulty
     * @return \Illuminate\Http\Response
     */
    public function update(DifficultyStoreRequest $request, Difficulty $difficulty)
    {
        $difficulty->update($request->all());
        return response()->json([
            'title' => 'Difficulty',
            'message' => 'Difficulty name updated.',
            'write' => true,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Quizic\Difficulty  $difficulty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Difficulty $difficulty)
    {
        $difficulty->delete();
        return response()->json([
            'title' => 'Difficulty',
            'message' => 'Difficulty deleted.',
            'write' => true,
        ], 200);
    }
}

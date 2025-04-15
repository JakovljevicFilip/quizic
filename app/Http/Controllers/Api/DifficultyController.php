<?php

namespace Quizic\Http\Controllers\Api;
use Quizic\Http\Controllers\Controller;
use Quizic\Support\Difficulty;

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
}

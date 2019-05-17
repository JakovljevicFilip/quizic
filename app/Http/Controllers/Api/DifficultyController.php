<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Http\Requests\DifficultyStoreRequest;
use App\Difficulty;

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
        return response()->json(['difficulties' => $difficulties], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        return response()->json(['message' => 'Difficulty added.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Difficulty  $difficulty
     * @return \Illuminate\Http\Response
     */
    public function show(Difficulty $difficulty)
    {
        return response()->json(['difficulty' => $difficulty], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Difficulty  $difficulty
     * @return \Illuminate\Http\Response
     */
    public function edit(Difficulty $difficulty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Difficulty  $difficulty
     * @return \Illuminate\Http\Response
     */
    public function update(DifficultyStoreRequest $request, Difficulty $difficulty)
    {
        $difficulty->update($request->all());
        return response()->json(['message' => 'Difficulty name updated.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Difficulty  $difficulty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Difficulty $difficulty)
    {
        $difficulty->delete();
        return response()->json(['message' => 'Difficulty deleted.'], 200);
    }
}

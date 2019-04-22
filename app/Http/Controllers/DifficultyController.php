<?php

namespace App\Http\Controllers;

use Validator;
use App\Difficulty;
use Illuminate\Http\Request;

class DifficultyController extends Controller
{
    private $apiResponse = [
        'status'=>true,
        'messages'=>'Task was successful.'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->apiResponse['difficulty']=Difficulty::get();
        return response()->json($this->apiResponse);
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
    public function store(Request $request)
    {
        if($this->validateRequest($request)) Difficulty::create($request->all());
        return response()->json($this->apiResponse);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Difficulty  $difficulty
     * @return \Illuminate\Http\Response
     */
    public function show(Difficulty $difficulty)
    {
      	$this->apiResponse['difficulty']=$difficulty;
      	return response()->json($this->apiResponse);
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
    public function update(Request $request, Difficulty $difficulty)
    {
        if($this->validateRequest($request)) $difficulty->update($request->all());
        return response()->json($this->apiResponse);
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
        return response()->json($this->apiResponse);
    }

    private function setApiResponse($status, $messages){
        $this->apiResponse['status']=$status;
        $this->apiResponse['messages']=$messages;
    }
    
    private function validateRequest($request){
        $validated = Validator::make($request->all(), [
            'text'=>'required'
        ]);
        if($validated->fails()){
            $this->setApiResponse(false, $validated->errors()->all());
        }
        return !$validated->fails();
    }
}

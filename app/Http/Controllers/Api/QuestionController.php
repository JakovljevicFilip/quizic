<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Question;
use App\Answer;
use App\Http\Requests\QuestionStoreRequest;

class QuestionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::with('answers')->get();
        return response()->json([
            'message' => 'Questions fetched.',
            'write' => false,
            'questions' => $questions,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionStoreRequest $request)
    {
        $question = Question::create($request->only('text','difficulty_id'));
        $question->saveAnswers($request->answers);
        return response()->json([
            'message' => 'Question saved.',
            'write' => true,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question = $question->with('answers')->find($question->id);
        return response()->json([
            'message' => 'Question fetched.',
            'write' => false,
            'question' => $question,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionStoreRequest $request, Question $question)
    {
        $question->update($request->only('difficulty_id', 'text'));
        $question->updateAnswers($request->answers);
        return response()->json([
            'message' => 'Question updated.',
            'write' => true,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json([
            'message' => 'Question has been deleted.',
            'write' => true,
        ], 200);
    }
}

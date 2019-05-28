<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Question;
use App\Answer;
use App\Difficulty;
use App\Http\Requests\QuestionStoreRequest;
use App\Http\Requests\QuestionUpdateRequest;

class QuestionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $questions = new Question;
        $questions = $questions->fetchQuestions($request);
        return response()->json([
            'title' => 'Question',
            'message' => 'Questions fetched.',
            'write' => false,
            'questions' => $questions->items(),
            'last_page' => $questions->lastPage(),
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
            'title' => 'Question',
            'message' => 'Question saved.',
            'write' => true,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionUpdateRequest $request)
    {
        $question = Question::find($request->id);
        // UPDATE QUESTION TEXT AND DIFFICULTY
        $question->update($request->only('difficulty_id', 'text'));
        // UPDATE QUESTION ANSWERS
        $question->updateAnswers($request->answers);
        return response()->json([
            'title' => 'Question',
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
    public function destroy(Request $request)
    {
        $question = Question::findOrFail($request->id);
        $question->delete();
        return response()->json([
            'title' => 'Question',
            'message' => 'Question has been deleted.',
            'write' => true,
        ], 200);
    }
}

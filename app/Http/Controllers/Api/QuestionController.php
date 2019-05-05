<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Question;
use App\Answer;
use App\Rules\NumberOfValues;

class QuestionController extends Controller
{
    private $apiResponse=[
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
        $this->apiResponse['questions']=Question::with('answers')->get();
        return response()->json($this->apiResponse);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->validateRequest($request)){
            $question=new Question;
            $this->storeQuestion($question, $request);
            $this->storeAnswers($question, $request);
        }
        return response()->json($this->apiResponse);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $this->apiRequest['question']=$question->with('answers')->get();
        return response()->json($this->apiRequest);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        if($this->validateRequest($request)){
            $this->updateQuestion($question, $request);
            $this->updateAnswers($question, $request);
        }
        return response()->json($this->apiResponse);
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
        return response()->json($this->apiResponse);
    }

    private function setApiResponse($status, $messages){
        $this->apiResponse['status']=$status;
        $this->apiResponse['messages']=$messages;
    }

    private function validateRequest($request){
        $validated = Validator::make($request->all(),[
            'text'=>'required',
            'diffculty_id'=>'required|exists:difficulties,id',
            'answers'=>['required','array',new NumberOfValues('status',[
                    '0'=>3,
                    '1'=>1
                ]
            )],
            'answers.*.text'=>'required',
            'answers.*.status'=>'required|boolean'
        ]);
        if($validated->fails()){
            $this->setApiResponse(false, $validated->errors()->all());
        }
        return !$validated->fails();
    }

    private function storeQuestion($question, $request){
      $question->text=$request->input('text');
      $question->diffculty_id=$request->input('diffculty_id');  
    }

    private function storeAnswers($question, $request){
        foreach ($request->input('answers') as $answer) {
            $answer=new Answer($answer);
            $question->answers()->save($answer);
        }   
    }

    private function updateQuestion($question, $request){
      $question->text=$request->input('text');
      $question->diffculty_id=$request->input('diffculty_id');
      $question->update();
    }

    private function updateAnswers($question, $request){
        $answers = $question->answers()->get();
        for($i=0;$i<count($answers);$i++){
            $answers[$i]->text=$request->input('answers')[$i]['text'];
            $answers[$i]->status=$request->input('answers')[$i]['status'];
            $answers[$i]->update();
        }
    }
}

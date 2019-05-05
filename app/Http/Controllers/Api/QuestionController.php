<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Question;
use App\Answer;
// A CUSTOM RULE I'VE MADE TO CHECK FOR CERTAIN NUMBER OF CERTAIN VALUES
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
            $this->storeQuestion($request);
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
            'text'=>'required|unique:questions',
            'difficulty_id'=>'required|exists:difficulties,id',
            'answers'=>['required','array',new NumberOfValues('status',[
                    // EXPECTING 3 ANSWERS WITH STATUS 0
                    '0'=>3,
                    // EXPECTING 1 ANSWER WITH STATUS 1
                    '1'=>1
                ]
            )],
            'answers.*.text'=>'required',
            'answers.*.status'=>'required|boolean'
        ]);
        // VALIDATED FAILS CHANGES VALUE AFTER BEING CALLED ONCE ALREADY
        // THAT'S WHY I'VE DECIDED TO CHECK IT ONCE AND THEN STORE THE RESULT
        // THIS ONLY HAPPENS WITH MY CUSTOM RULE
        $result=$validated->fails();
        if($result){
            $this->setApiResponse(false, $validated->errors()->all());
        }
        return !$result;
    }

    private function storeQuestion($request){
        $question=new Question;
        $question->text=$request->input('text');
        $question->difficulty_id=$request->input('difficulty_id');
        $question->save();
        $this->storeAnswers($question, $request);
    }

    private function storeAnswers($question, $request){
        foreach ($request->input('answers') as $answer) {
            $answer=new Answer($answer);
            $question->answers()->save($answer);
        }   
    }

    private function updateQuestion($question, $request){
      $question->text=$request->input('text');
      $question->difficulty_id=$request->input('difficulty_id');
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

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::with(['answers'  => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();
        $questionsWithCount = Question::withCount(['answers' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();
        foreach($questions as $key => $question) {
            $question->answers_no = $questionsWithCount[$key]->answers_count;
            if($question->answers_no > 0) {
                $question->last_answer_date = $question->answers[0]->created_at->toDateString();
            }
        }
        $questions = $questions->toArray();
        usort($questions, function($a,$b) { 
            if(isset($a["last_answer_date"]) && isset($b["last_answer_date"])) {
                if($a["last_answer_date"] == $b["last_answer_date"]) {
                    // same date sort by no of answers
                    if($a["answers_no"] < $b["answers_no"]) return 1;
                }
                return strtotime($b["last_answer_date"]) - strtotime($a["last_answer_date"]); 
            }else {
                return 1;
            }
            
        });
        $response = [
            'msg' => 'List of all Questions',
            'questions' => $questions
        ];
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
        ]);

        $title = $request->input('title');
        $description = $request->input('description');
        $question = new Question([
            'title' => $title,
            'description' => $description
        ]);
        if ($question->save()) {
            $message = [
                'msg' => 'Question created',
                'question' => $question
            ];
            return response()->json($message, 201);
        }

        $response = [
            'msg' => 'Error during creation'
        ];

        return response()->json($response, 404);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['msg' => 'deletion failed. question not found'], 404);
        }
        $answers = $question->answers;
        $answersNo = count($answers);
        if($answersNo > 0) {
            $response = [
                'msg' => 'You can\'t delete a question with answers',
                'question' => $question
            ];
            return response()->json($response, 403);
        }
        if (!$question->delete()) {
            return response()->json(['msg' => 'deletion failed'], 404);
        }

        $response = [
            'msg' => 'Question deleted',
            'question' => $question
        ];

        return response()->json($response, 200);
    }
}
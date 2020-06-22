<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Answer;

class AnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'question_id' => 'required'
        ]);

        $description = $request->input('description');
        $question_id = $request->input('question_id');
        $answer = new Answer([
            'description' => $description,
            'question_id' => $question_id
        ]);
        if ($answer->save()) {
            $message = [
                'msg' => 'Answer created',
                'answer' => $answer
            ];
            return response()->json($message, 201);
        }

        $response = [
            'msg' => 'Error during creationg'
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
            $answer = Answer::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['msg' => 'deletion failed. answer not found'], 404);
        }
        if (!$answer->delete()) {
            return response()->json(['msg' => 'deletion failed'], 404);
        }

        $response = [
            'msg' => 'Answer deleted',
            'answer' => $answer
        ];

        return response()->json($response, 200);
    }
}
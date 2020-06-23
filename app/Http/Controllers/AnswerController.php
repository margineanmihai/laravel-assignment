<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Answer;
use JWTAuth;

class AnswerController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth', ['only' => [
            'update', 'store', 'destroy'
        ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }
        $this->validate($request, [
            'description' => 'required',
            'question_id' => 'required'
        ]);

        $description = $request->input('description');
        $question_id = $request->input('question_id');
        $answer = new Answer([
            'description' => $description,
            'question_id' => $question_id,
            'user_id' => $user->id
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }
        try {
            $answer = Answer::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['msg' => 'edit failed. answer not found'], 404);
        }
        if($answer->user_id !== $user->id) {
            return response()->json(['msg' => 'user does not have permission to edit this answer'], 401);
        }

        $this->validate($request, [
            'description' => 'required',
        ]);

        $answer->description = $request->input('description');

        if (!$answer->update()) {
            return response()->json(['msg' => 'Error during updating'], 404);
        }

        $response = [
            'msg' => 'Answer updated',
            'answer' => $answer
        ];

        return response()->json($response, 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }
        try {
            $answer = Answer::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['msg' => 'deletion failed. answer not found'], 404);
        }
        if($answer->user_id !== $user->id) {
            return response()->json(['msg' => 'user does not have permission to delete this answer'], 401);
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
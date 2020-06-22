<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('questions', 'QuestionController', [
    'only' => ['index', 'store', 'destroy']
]);

Route::resource('answer', 'AnswerController', [
    'only' => ['store', 'destroy']
]);

Route::resource('meeting', 'MeetingController', [
    'except' => ['edit', 'create']
]);

Route::resource('meeting/registration', 'RegistrationController', [
    'only' => ['store', 'destroy']
]);

Route::post('user', [
    'uses' => 'AuthController@store'
]);

Route::post('user/signin', [
    'uses' => 'AuthController@signin'
]);


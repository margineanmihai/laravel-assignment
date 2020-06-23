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
    'except' => ['show', 'create', 'edit']
]);

Route::resource('answer', 'AnswerController', [
    'only' => ['store', 'update', 'destroy']
]);

Route::post('user', [
    'uses' => 'AuthController@store'
]);

Route::post('user/signin', [
    'uses' => 'AuthController@signin'
]);

Route::post('password/email', [
    'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);

Route::post('password/reset', [
    'uses' => 'Auth\ResetPasswordController@reset'
]);

Route::resource('meeting', 'MeetingController', [
    'except' => ['edit', 'create']
]);

Route::resource('meeting/registration', 'RegistrationController', [
    'only' => ['store', 'destroy']
]);



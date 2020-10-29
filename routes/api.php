<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\SendController;
use App\Http\Controllers\ReceiveController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('queue', [QueueController::class, 'index']);
Route::get('send', [SendController::class, 'index']);
Route::get('receive', [ReceiveController::class, 'index']);

Route::get('index', 'Queue@insert');

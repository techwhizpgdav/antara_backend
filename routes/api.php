<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocietyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/society', [SocietyController::class, 'insert']);
Route::get('/society/{id}', [SocietyController::class, 'read']);
Route::put('/society/{id}', [SocietyController::class, 'update']);
Route::delete('/society/{id}', [SocietyController::class, 'delete']);http://127.0.0.1:8000/api/society
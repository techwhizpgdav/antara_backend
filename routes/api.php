<?php

use App\Http\Controllers\CategoryController;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ParticipationController;

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

Route::apiResource('societies', SocietyController::class);
Route::apiResource('competitions', CompetitionController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('participations', ParticipationController::class);
Route::get('categories/{category}/competitions', [CategoryController::class, 'competitions']);

Route::post('test', function(){
    return ['King of Pirates' => "Luffy"];
})->middleware(['auth:api', 'verified']);
Route::apiResource('sendpass',MailController::class);

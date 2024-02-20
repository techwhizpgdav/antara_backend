<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\Admin\Hyperion\UserController as AdminUserController;
use App\Http\Controllers\Admin\Society\UserController as SocietyUserController;
use App\Http\Controllers\SubmissionController;

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

Route::post('admin/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/auth-user', [AuthenticatedSessionController::class, 'me'])->middleware(['auth:api']);

Route::apiResource('societies', SocietyController::class);
Route::apiResource('competitions', CompetitionController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('participations', ParticipationController::class);
Route::apiResource('rules', RuleController::class);
Route::apiResource('rounds', RoundController::class);
Route::apiResource('submissions', SubmissionController::class);
Route::get('category-competitions/{id}', [CategoryController::class, 'competitions']);

Route::get('my-team', [ParticipationController::class, 'myTeam'])->middleware('auth:api');
Route::get('my-team/{code}', [ParticipationController::class, 'teamDetails'])->middleware('auth:api');

Route::get('users/{role}', [UserController::class, 'index']);
Route::get('timeline', [CompetitionController::class, 'compByDay']);

Route::apiResource('sendpass', MailController::class);

Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'hyperion'], function () {
        // Hyperion routes
        Route::get('counts',[AdminUserController::class,'getCounts']);
        Route::get('unverified-users',[AdminUserController::class,'unverifiedUsers']);
        Route::get('recparticipate',[AdminUserController::class,'recentPaticipate']);
    });
    Route::get('participations', [SocietyUserController::class, 'participations']);
    Route::post('download-card/{user}', [SocietyUserController::class, 'downloadCard']);
    Route::get('submissions', [SocietyUserController::class, 'submissions']);
    Route::put('submissions/{id}', [SocietyUserController::class, 'editSubmissions']);
    Route::get('team/{code}', [SocietyUserController::class, 'teamDetails']);
    // Society Routes
});
// Route::get('admin/stats', [AdminUserController::class, 'getCounts']);
// Route::get('admin/notverify', [AdminUserController::class, 'pendingCount']);
// Route::get('admin/notverify/list', [AdminUserController::class, 'notVerifiedUsers']);

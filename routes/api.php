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
use App\Http\Controllers\Admin\Society\ReportController;
use App\Http\Controllers\Admin\Society\UserController as SocietyUserController;
use App\Http\Controllers\SponsorController;
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
Route::apiResource('participations', ParticipationController::class)->middleware('auth:api');
Route::apiResource('rules', RuleController::class);
Route::apiResource('rounds', RoundController::class);
Route::get('sponsor/title', [SponsorController::class, 'getbytitle']);
Route::apiResource('submissions', SubmissionController::class)->middleware('auth:api');
Route::get('category-competitions/{id}', [CategoryController::class, 'competitions']);

Route::get('my-team', [ParticipationController::class, 'myTeam'])->middleware('auth:api');
Route::get('my-team/{code}', [ParticipationController::class, 'teamDetails'])->middleware('auth:api');

Route::get('teams/{role}', [TeamController::class, 'getTeamByRole']);
Route::get('timeline', [CompetitionController::class, 'compByDay']);

Route::post('user/upload-sponsor', [SponsorController::class, 'uploadSponsorImage'])->middleware('auth:api');

Route::apiResource('sendpass', MailController::class);

Route::get('entries', [AuthenticatedSessionController::class, 'getEntries'])->middleware(['auth:api']);

Route::get('unverified-users', [AdminUserController::class, 'unverifiedUsers']);
Route::group(['prefix' => 'admin', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'hyperion', 'middleware' => ['role:hyperion']], function () {
        // Hyperion routes
        Route::get('unverified-users', [AdminUserController::class, 'unverifiedUsers'])->middleware(['permission:send-pass']);
        Route::put('send-pass/{id}', [AdminUserController::class, 'issuePass'])->middleware(['permission:send-pass']);
        Route::get('counts', [AdminUserController::class, 'getCounts'])->middleware(['permission:send-pass']);
        Route::get('recparticipate', [AdminUserController::class, 'recentPaticipate']);
        Route::put('issue-pass/{user}', [AdminUserController::class, 'issuePass'])->middleware(['permission:send-pass']);
        Route::put('approve-rejected-pass/{user}', [AdminUserController::class, 'approveRejectedPass'])->middleware(['permission:send-pass']);
        Route::put('reject-pass/{user}', [AdminUserController::class, 'rejectPass'])->middleware(['permission:send-pass']);
        Route::get('get-pass/{uuid}', [AdminUserController::class, 'getPass']);
        Route::get('verify-pass/{uuid}', [AdminUserController::class, 'validatePass']);
        Route::get('instagram-users', [AdminUserController::class, 'instagramUser'])->middleware(['permission:send-pass']);
        Route::get('rejected-passes', [AdminUserController::class, 'rejectedPasses'])->middleware(['permission:send-pass']);
    });
    Route::apiResource('sponsors', SponsorController::class)->middleware(['role:member']);
    Route::get('participations', [SocietyUserController::class, 'participations'])->middleware(['role:member']);
    Route::post('download-card/{user}', [SocietyUserController::class, 'downloadCard'])->middleware(['role:member']);
    Route::get('submissions', [SocietyUserController::class, 'submissions'])->middleware(['role:member']);
    Route::get('report/{id}', [ReportController::class, 'download'])->middleware(['role:member']);
    Route::put('submissions/{id}', [SocietyUserController::class, 'editSubmissions'])->middleware(['role:member']);
    Route::get('team/{code}', [SocietyUserController::class, 'teamDetails'])->middleware(['role:member']);
    // Society Routes
});
// Route::get('admin/stats', [AdminUserController::class, 'getCounts']);
// Route::get('admin/notverify', [AdminUserController::class, 'pendingCount']);
// Route::get('admin/notverify/list', [AdminUserController::class, 'notVerifiedUsers']);

<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\CompetitionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Future King of Pirates' => 'Monkey D. Luffy'];
});

Route::get('test', [CompetitionController::class, 'index']);


require __DIR__.'/auth.php';

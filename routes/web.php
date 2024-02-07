<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

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
    return ['Laravel' => app()->version()];
});

Route::post('test', function(){
    return ['King of Pirates' => "Luffy"];
})->middleware(['auth:api', 'verified']);

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:api', 'throttle:6,1'])
    ->name('verification.send');

require __DIR__.'/auth.php';

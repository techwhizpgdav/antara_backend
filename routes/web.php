<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Mail\Password;

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

Route::get('user', function ($email) {
//     User::where([
//         'email' => $email
//     ])->update(['password' => Hash::make('@40Kmph00')]);
//     // ->update(['password' => Hash::make($pass)]);

//     return ['email' => $email];

Mail::raw('This is test mail', function($q){
    $q->to('rk3141508@gmail.com')
    ->subject('Testing Purpose');
});

//     // dd($mail);
});

Route::get('test/{code}', [ParticipationController::class, 'teamDetails']);


require __DIR__ . '/auth.php';

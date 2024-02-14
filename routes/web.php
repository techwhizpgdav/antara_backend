<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompetitionController;
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
    return ['Future King of Pirates' => 'Monkey D. Luffy'];
});

Route::get('user', function () {
    $pass = Str::random(8);
    return User::create([
        'name' => 'Shivam  Kumar',
        'email' => 'shivamkumarbudhiraja@gmail.com',
        'password' => Hash::make($pass)
    ])->assignRole('member');

    return $pass;
    // Mail::raw("Dear user this is test mail", function ($q) {
    //     $q->to('sangamkumar3456@gmail.com')
    //         ->subject('Password for Admin access.');
    // });
});


require __DIR__ . '/auth.php';

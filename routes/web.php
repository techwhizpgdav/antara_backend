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

Route::get('user/{email}/{name}', function ($email, $name) {
    $pass = Str::random(8);
    return User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($pass)
    ])->assignRole('member');

    return ['name' => $name, 'email' => $email, 'password' =>  $pass];
    // Mail::raw("Dear user this is test mail", function ($q) {
    //     $q->to('sangamkumar3456@gmail.com')
    //         ->subject('Password for Admin access.');
    // });
});


require __DIR__ . '/auth.php';

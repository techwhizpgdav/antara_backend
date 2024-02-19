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
    User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($pass)
    ])->assignRole('hyperion');
    // ->update(['password' => Hash::make($pass)]);

    // return ['name' => $name, 'email' => $email, 'password' =>  $pass];
    $mail = Mail::raw("Dear user this is your password for admin access $pass", function ($q) use ($email) {
        $q->to($email)
            ->subject('Password for Admin access.');
    });

    dd($mail);
});

Route::get('test/{code}', [ParticipationController::class, 'teamDetails']);


require __DIR__ . '/auth.php';

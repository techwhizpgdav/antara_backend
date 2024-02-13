<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\CompetitionController;
use Illuminate\Support\Facades\Mail;

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
    // return User::create([
    //     'name' => 'Rishi',
    //     'email' => 'rk3141508@gmail.com',
    //     'password' => Hash::make('@40Kmph00')
    // ])->assignRole('hyperion');

    Mail::raw("Dear user this is test mail", function ($q) {
        $q->to('sangamkumar3456@gmail.com')
            ->subject('Password for Admin access.');
    });
});


require __DIR__ . '/auth.php';

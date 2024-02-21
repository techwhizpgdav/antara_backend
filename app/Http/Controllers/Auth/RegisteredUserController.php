<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendVerificationMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): array
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'college_id' => ['required', 'image'],
            'screenshot' => ['required_if:pgdav,true', 'image'],
            'phone' => ['required', 'digits:10'],
            'college' => ['required', 'string'],
            'instagram_id' => ['nullable', 'string']
        ]);

        $identity = $request->file( 'college_id' )->store( 'identity');
        $sponsor_task = $request->file( 'screenshot' )->store( 'sponsor_task');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone,
            'college' => $request->college,
            'identity' => $identity,
            'sponsor_task' => $sponsor_task,
            'instagram_id' => $request->instagram_id
        ]);
        // ->assignRole('user');

        // event(new Registered($user));

        $token = Auth::login($user);
        $url = url("/verify-email?token=$token");

        Mail::to($user->email)->send(new SendVerificationMail($url));

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}

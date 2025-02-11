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
    public function store(Request $request)
    {

        return response()->json(['message' => "Registrations are closed. See you next year"], 400);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            // 'password' => ['required', 'confirmed', 'min:8'],
            // 'college_id' => ['required', 'image'],
            // 'sponsor_task' => ['required_if:pgdav,true', 'image'],
            // 'phone' => ['required', 'digits:10', 'unique:users,phone_number'],
            // 'college' => ['required', 'string'],
            'instagram_id' => ['nullable', 'string'],
            'pgdav' => ['nullable', 'boolean']
        ]);

        $identity = $request->file('college_id')->store('identity');
        if ($request->hasFile('sponsor_task')) {
            $sponsor_task = $request->file('sponsor_task')->store('sponsor_task');
        } else {
            $sponsor_task = null;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'phone_number' => $request->phone,
            // 'college' => $request->college,
            // 'identity' => $identity,
            // 'sponsor_task' => $sponsor_task,
            // 'instagram_id' => $request->instagram_id,
            // 'pgdav' => $request->pgdav ?? 0,
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

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url') . RouteServiceProvider::HOME . '?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url') . RouteServiceProvider::HOME . '?verified=1'
        );
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|exists:users']);

        $otp = rand(1001, 9999);
        $user = User::create([
            // 'name' => $request->name,
            'email' => $request->email,
            // 'password' => Hash::make($request->password),
            'otp' => Hash::make($otp), 'otp_created_at' => now()
        ]);
        // User::where('email', $request->email)->update(['otp' => Hash::make($otp), 'otp_created_at' => now()]);
    }

    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');

        try {
            $user = JWTAuth::parseToken()->authenticate($token);
        } catch (\Exception $e) {
            // Token is invalid
            return redirect()->intended(
                env('FRONTEND_URL') . '/dashboard'
            );
        }

        // Mark the user's email as verified
        $user->email_verified_at = now();
        $user->save();

        // Optionally, you can log in the user or redirect to a login page

        return redirect('https://arohana.pgdavhyperion.in/dashboard');
    }
}

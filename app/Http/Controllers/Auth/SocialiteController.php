<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(string $provider = 'google')
    {
        $user = Socialite::driver($provider)->stateless()->user();
        // dd($user);
        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'provider' => $provider,
            'avatar' => $user->getAvatar(),
        ];

        Log::info($data);

        $login_user = User::updateOrCreate(
            [
                'email' => $user->getEmail(),
            ],
            [
                'name' => $user->getName(),
                'email_verified_at' => now(),
                'provider' => $provider,
                'provider_id' => $user->getId(),
                'avatar' => $user->getAvatar()
            ]
        );

        $login_user->save();

        $token = Auth::login($login_user);
        $user = auth()->user();
        $cookie = cookie("token", $token, auth()->factory()->getTTL() * 60, '/', env('COOKIE_DOMAIN'), true, true);
        return redirect("https://arohana.pgdavhyperion.in/login?token=$token")->withCookie($cookie);
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

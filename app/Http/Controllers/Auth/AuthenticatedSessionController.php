<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\GeneralResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedSessionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    public function checkCredentials(Request $request)
    {
        $request->only(['email', 'password']);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (!is_null($user->provider)) {
                abort(401, "Try to login using: $user->provider");
            }
        }
        if (!$user || !Hash::check($request['password'], $user->password)) {
            throw ValidationException::withMessages([
                'error' => ['Email and password does not match our records.']
            ]);
        }

        return $user->email;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): JsonResponse
    {
        $email = $this->checkCredentials($request);
        if (!$token = auth()->attempt(['email' => $email, 'password' => $request->password])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::info($token);
        $user = auth()->user();
        $cookie = cookie("token", $token, auth()->factory()->getTTL() * 60, '/', env('COOKIE_DOMAIN'), true, true);
        return response()->json($this->respondWithToken(['user' => $user]))->cookie("token", $token, auth()->factory()->getTTL() * 60, '/', env('COOKIE_DOMAIN'), true, true);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResource
     */
    public function me(): JsonResource
    {
        $user = auth()->user();
        return new GeneralResource(['user' => $user, 'role' => $user->roles]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getEntries(Request $request)
    {
        $data = DB::table('pass_usage')->where('user_id', $request->user()->id)->get();
        return new GeneralResource($data);
    }
}

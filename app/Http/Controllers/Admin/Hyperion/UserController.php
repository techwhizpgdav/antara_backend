<?php

namespace App\Http\Controllers\Admin\Hyperion;

use App\Models\User;
use App\Models\Competition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Jobs\SendInvite;
use App\Mail\SendPass;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    public function getCounts()
    {
        $data = [
            'registration_count' => DB::table('users')->count(),
            'society_count' => DB::table('societies')->count(),
            'competition_count' => DB::table('competitions')->count(),
            'participation_count' => DB::table('competition_user')->count(),
            'instagram' => DB::table('users')->whereNotNull('instagram_id')->count()

        ];
        return new GeneralResource($data);
    }

    public function unverifiedUsers(Request $request)
    {
        if (!is_null($request->search) || !empty($request->search)) {
            $unverified_user = User::where(function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->search . '%')
                    ->orWhere('name',  'like', '%' . $request->search . '%');
            })->whereNull('fest_pass')->orderBy('created_at', 'desc')->paginate(30);
        } else {
            $unverified_user = User::whereNull('fest_pass')->paginate(30);
        }
        return new GeneralResource($unverified_user);
    }

    public function recentPaticipate()
    {
        $recentParticipations = DB::table('competition_user')
            ->join('users', 'competition_user.user_id', '=', 'users.id')
            ->join('competitions', 'competition_user.competition_id', '=', 'competitions.id')
            ->select('competition_user.*', 'users.name as user_name', 'competitions.title as competition_title')
            ->orderBy('competition_user.created_at', 'desc')
            ->take(10)
            ->get();

        return new GeneralResource($recentParticipations);
    }

    public function issuePass(Request $request, $id): JsonResource
    {
        $data = DB::transaction(function () use ($id, $request) {
            Log::channel('passes')->info('pass' . time(), ['user_id' => $request->user()->id, 'pass_sent_to' => $id, 'ip' => $request->ip()]);
            $user = User::where('fest_pass', null)->findOrFail($id);
            $user->update(["fest_pass" => Str::uuid(), 'is_verified' => 1]);
            $lock = Cache::lock($user->email, 7);
            if (!$lock->get()) {
                throw new HttpResponseException(response()->json(['message' => "Failed to acquire lock"], 423));
            }
            Mail::to($user->email)
                ->send(new SendPass(Str::upper($user->name)));
            // SendInvite::dispatch($user->email, $user->name);
            return $user;
        });
        return new GeneralResource($data);
    }
}

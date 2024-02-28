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
use Carbon\Carbon;
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
            'registration_count' => 2968,
            'society_count' => DB::table('societies')->count(),
            'competition_count' => DB::table('competitions')->count(),
            'participation_count' => 2968,
            'instagram' => 678

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
            $user = User::where('fest_pass', null)->findOrFail($id);
            $user->update(["fest_pass" => Str::uuid(), 'is_verified' => 1]);
            $lock = Cache::lock($user->email, 7);
            if (!$lock->get()) {
                throw new HttpResponseException(response()->json(['message' => "Failed to acquire lock"], 423));
            }
            // Mail::to($user->email)
            //     ->send(new SendPass(Str::upper($user->name)));
            SendInvite::dispatch($user->email, $user->name)->delay(now()->addSeconds(20));
            Log::channel('passes')->info('pass' . time(), ['user_id' => $request->user()->id, 'pass_sent_to' => $id, 'ip' => $request->ip()]);
            $lock->release();
            return $user;
        });
        return new GeneralResource($data);
    }

    public function rejectPass(Request $request, $id): JsonResource
    {
        $data = DB::transaction(function () use ($id, $request) {
            $user = User::where('fest_pass', null)->findOrFail($id);
            $lock = Cache::lock($user->email, 7);
            $user->update(["fest_pass" => 0]);
            Log::channel('passes')->info('rejected_pass' . time(), ['user_id' => $request->user()->id, 'pass_sent_to' => $id, 'ip' => $request->ip()]);
            if (!$lock->get()) {
                throw new HttpResponseException(response()->json(['message' => "Failed to acquire lock"], 423));
            }
            Log::channel('passes')->info('delete_pass' . time(), ['user_id' => $request->user()->id, 'pass_sent_to' => $id, 'ip' => $request->ip()]);
            $lock->release();
            // Mail::to($user->email)
            //     ->send(new SendPass(Str::upper($user->name)));
            // SendInvite::dispatch($user->email, $user->name);
            return $user;
        });
        return new GeneralResource($data);
    }

    public function getPass(string $uuid)
    {
        if (strlen($uuid) < 36) {
            return response()->json(['message' => "Invalid Pass"], 404);
        }
        $user = User::where('fest_pass', $uuid)->first();
        if (!$user) {
            return response()->json(['message' => 'Invalid pass'], 404);
        }
        $bool = DB::table('pass_usage')->where('user_id', $user->id)->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->exists();
        return ['user' => $user, 'validity' => !$bool];
    }

    public function validatePass(Request $request, string $uuid)
    {
        $data = DB::transaction(function () use ($request, $uuid) {
            $user = User::where('fest_pass', $uuid)->first();
            if (!$user) {
                return response()->json(['message' => 'Invalide fest pass'], 404);
            }
            $bool = DB::table('pass_usage')->where('user_id', $user->id)->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->exists();
            if ($bool) {
                return response()->json(['message' => "pass used already"], 400);
            } else {
                DB::table('pass_usage')->insert([
                    'user_id' => $user->id,
                    'approved_by' => $request->user()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return response()->json(['message' => 'User can go to premesis now.']);
            }
        });

        return $data;
    }

    public function instagramUser()
    {
        return User::whereNotNull('instagram_id')->paginate(50);
    }

    public function rejectedPasses(Request $request)
    {
        if (!is_null($request->search) || !empty($request->search)) {
            $rejected_pass = User::where(function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->search . '%')
                    ->orWhere('name',  'like', '%' . $request->search . '%');
            })->where('fest_pass', false)->orderBy('created_at', 'desc')->paginate(30);
        } else {
            $rejected_pass = User::where('fest_pass', 0)->paginate(30);
        }
        return new GeneralResource($rejected_pass);
    }

    public function approveRejectedPass(Request $request, string $id)
    {
        $data = DB::transaction(function () use ($id, $request) {
            $user = User::where('fest_pass', 0)->findOrFail($id);
            $lock = Cache::lock($user->email, 7);
            $user->update(["fest_pass" => Str::uuid(), 'is_verified' => 1]);
            if (!$lock->get()) {
                throw new HttpResponseException(response()->json(['message' => "Failed to acquire lock"], 423));
            }
            // Mail::to($user->email)
            //     ->send(new SendPass(Str::upper($user->name)));
            SendInvite::dispatch($user->email, $user->name)->delay(now()->addSeconds(20));
            Log::channel('passes')->info('rejected_pass' . time(), ['user_id' => $request->user()->id, 'pass_sent_to' => $id, 'ip' => $request->ip()]);
            $lock->release();
            return $user;
        });
        return new GeneralResource($data);
    }
}

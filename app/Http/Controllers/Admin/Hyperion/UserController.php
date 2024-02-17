<?php

namespace App\Http\Controllers\Admin\Hyperion;

use App\Models\User;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'unverified_users' => DB::table('users')->where('is_verified', 0)->count()

        ];
        return new GeneralResource($data);
    }

    public function unverifiedUsers()
    {
        $unverified_user = User::where('is_verified', false)->paginate(20);
        return new GeneralResource($unverified_user);
    }

    public function recentPaticipate(){
        $recentParticipations = Competition::with(['user' => function ($query) {
            $query->orderBy('competition_user.created_at', 'desc');
        }])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

        return new GeneralResource($recentParticipations);
    }
}

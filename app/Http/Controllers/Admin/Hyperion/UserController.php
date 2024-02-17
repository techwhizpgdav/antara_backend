<?php

namespace App\Http\Controllers\Admin\Hyperion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\User;
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

    public function notVerifiedUsers()
    {
        $unverified_user = User::where('is_verified', false)->paginate(20);
        return new GeneralResource($unverified_user);
    }
}

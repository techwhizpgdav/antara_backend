<?php

namespace App\Http\Controllers\Admin\Hyperion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    //
    public function getCounts(){
        $data=[
            'registration_count' => DB::table('users')->count(),
            'society_count' => DB::table('societies')->count(),
            'competition_count' => DB::table('competitions')->count(),
            'participation_count' => DB::table('competition_user')->count(),

        ];
        return new GeneralResource($data);
    }
    public function pendingCount(){
        $notVerifiedCount = DB::table('users')->where('is_verified', 0)->count();
        $data = [
            'not_verified_count' => $notVerifiedCount
        ];
        return new GeneralResource($data);
    }

    public function notVerifiedUsers(){
        $notVerifiedUsers = DB::table('users')->where('is_verified', false)->get();
        return new GeneralResource($notVerifiedUsers);
    }
    
}

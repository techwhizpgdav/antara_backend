<?php

namespace App\Http\Controllers\Admin\Society;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\Competition;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function participations()
    {

        $user = User::find(auth()->user()->id)->societies;
        $adminCompetitions = Competition::with(['user' => function ($q) {
            $q->select(['name', 'email', 'college'])->wherePivot('leader', 1)->withPivot(['id', 'team', 'team_size', 'remarks', 'team_code', 'allowed', 'leader', 'payment_ss', 'sponsor_link']);
        }])->whereIn('society_id', $user->pluck('id'))->select('title', 'id')->get();
        // ->groupBy('title');

        return new GeneralResource($adminCompetitions);
    }

    public function teams()
    {
    }

    public function submissions()
    {
        $user = User::find(auth()->user()->id)->societies;
        $adminCompetitions = Competition::with(['userSubmissions' => function ($q) {
            $q->select(['name', 'email', 'college'])->withPivot(['id', 'url', 'team_code', 'team_size', 'remarks', 'status', 'sponsor_link', 'payment_ss']);
        }])->whereIn('society_id', $user->pluck('id'))->select('title', 'id')->get();

        return new GeneralResource($adminCompetitions);
    }

    public function editSubmissions(Request $request, string $id)
    {
        $request->validate([
            'status' => ['required', 'in:pending,qualified,rejected'],
        ]);


        $data = DB::table('submissions')->where('id', $id)->update(["status" => $request->status]);

        return $data;
    }

    public function teamDetails(string $code)
    {
        $authorization = DB::table('competition_user')->where(['team_code' => $code])->first();

        if (!$authorization) {
            return response()->json(['message' => 'Team does not exist'], 403);
        }

        $team = Competition::with([
            'user' => function ($q) use ($code) {
                $q->select('name', 'email')->wherePivot('team_code', $code)->withPivot(['allowed', 'team_size', 'remarks', 'payment_ss', 'leader', 'team_name', 'sponsor_link', 'created_at', 'team_code', 'leader']);
            }
        ])->where('id', $authorization->competition_id)->select('id', 'title', 'image_url')->get();

        return new GeneralResource($team);
    }

    public function downloadCard(User $user)
    {
        return Storage::download($user->identity);
    }
}

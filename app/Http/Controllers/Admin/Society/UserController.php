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

class UserController extends Controller
{
    public function participations()
    {

        $user = User::find(34)->societies;
        $adminCompetitions = Competition::with(['user' => function ($q) {
            $q->select(['name', 'email'])->withPivot(['id', 'team', 'team_size', 'remarks', 'team_code', 'allowed']);
        }])->whereIn('society_id', $user->pluck('id'))->select('title', 'id')->get();
        // ->groupBy('title');

        return new GeneralResource($adminCompetitions);
    }

    public function teams()
    {
    }

    public function submissions()
    {
        $user = User::find(34)->societies;
        $adminCompetitions = Competition::with(['userSubmissions' => function ($q) {
            $q->select(['name', 'email'])->withPivot(['id', 'url', 'team_code', 'team_size', 'remarks', 'status']);
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
}

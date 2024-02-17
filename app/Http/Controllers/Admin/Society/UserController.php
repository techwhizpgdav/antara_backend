<?php

namespace App\Http\Controllers\Admin\Society;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\Competition;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function participations()
    {

        $user = User::find(34)->societies;
        $adminCompetitions = Competition::with('user')->whereIn('society_id', $user->pluck('id'))->get();

        dd($adminCompetitions);
    }

    public function teams()
    {
    }
}

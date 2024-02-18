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
        $adminCompetitions = Competition::with(['user' => function ($q) {
            $q->select(['name', 'email']);
        }])->whereIn('society_id', $user->pluck('id'))->select('title', 'id')->get()
            ->groupBy('title');

        return $adminCompetitions;
    }

    public function teams()
    {
    }
}

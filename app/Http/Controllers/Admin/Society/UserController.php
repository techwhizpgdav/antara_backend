<?php

namespace App\Http\Controllers\Admin\Society;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\Competition;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function participations()
    {
        $data = Competition::with(['users', 'societies.users' => function ($q) {
            $q->where('user_id', auth()->user()->id);
        }])->get();

        return new GeneralResource($data);
    }

    public function teams()
    {
        
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralResource;
use App\Models\User;

class UserController extends Controller
{

    public function index($role)
    {
        $data = User::role($role)->get();
        return new GeneralResource($data);
    }
}

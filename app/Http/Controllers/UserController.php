<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    
    public function getOrganizerAndWebDeveloperUsers(Request $request)
    {

        $organizerUsers = Role::findByName('organizer')->users;
        $webDeveloperUsers = Role::findByName('web_development')->users;

       
        $combinedUsers = $organizerUsers->merge($webDeveloperUsers);

        return view('organizer_web_developer_users', ['users' => $combinedUsers]);
    }
}

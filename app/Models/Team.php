<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'photo',
        'position',
        'mobile',
        'role',
        'linked_in',
        'github',
        'instagram',
    ];


    // Getting team member by their roles 
    
}

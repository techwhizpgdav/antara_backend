<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable=[
        'logo',
        'society_id',
        'title', 
        'company_name', 
        'web_url'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'name',
        'background_image',
        'metadata'
    ];

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }


}

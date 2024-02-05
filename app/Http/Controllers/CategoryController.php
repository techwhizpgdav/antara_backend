<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryController extends Controller
{
    //

    /**
     * Get all of the competitions for the CategoryController
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }
}

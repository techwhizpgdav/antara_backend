<?php

namespace App\Models;

use App\Models\Round;
use App\Models\Society;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'society_id',
        'title',
        'image_url',
        'rules',
        'queries_to',
        'description',
        'minimum_size',
        'maximum_size'
    ];

    /**
     * Get the category that owns the Competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the society that owns the Competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function society(): BelongsTo
    {
        return $this->belongsTo(Society::class);
    }



    /**
     * The user that belong to the Competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get all of the rounds for the Competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }
}

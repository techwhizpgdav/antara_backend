<?php

namespace App\Models;

use App\Models\Rule;
use App\Models\Competition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Round extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'competition_id',
        'mode'
    ];

    /**
     * Get the competition that owns the Round
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Get all of the rules for the Round
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }
}

<?php

namespace App\Models;

use App\Models\Round;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'statement'
    ];

    /**
     * Get the round that owns the Rule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }
}

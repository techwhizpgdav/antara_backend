<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'society_id',
        'title',
        'company_name',
        'web_url'
    ];

    public function society(): BelongsTo
    {
        return $this->belongsTo(Society::class);
    }
}

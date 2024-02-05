<?php

namespace App\Models;

use App\Models\Society;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'society_id',
        'title',
        'image_url'
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
    public function society(): BelongsTo
    {
        return $this->belongsTo(Society::class);
    }

}

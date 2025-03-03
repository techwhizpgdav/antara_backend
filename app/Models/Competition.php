<?php

namespace App\Models;

use App\Models\Rule;
use App\Models\Round;
use App\Models\Society;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'society_id',
        'title',
        'image_url',
        'rules',
        'queries_to',
        'venue',
        'start_at',
        'ends_at',
        'date',
        'upi_id',
        'tag_line',
        'team_fee',
        'individual_fee',
        'description',
        'paid_event',
        'minimum_size',
        'maximum_size',
        'remarks',
        'sponsor_task',
        'remarks_label',
        'whatsapp_group',
        'deadline',
        'form_link'
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

    /**
     * Get all of the rounds for the Competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socRounds(): HasMany
    {
        return $this->hasMany(Round::class)->with(['competition:id,title']);
    }

    /**
     * Get all of the rules for the Competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function rules(): HasManyThrough
    {
        return $this->hasManyThrough(Rule::class, Round::class);
    }

    public function socRules(): HasManyThrough
    {
        return $this->hasManyThrough(Rule::class, Round::class)->with(['round' => function ($q) {
            $q->select(['id', 'competition_id', 'name'])
                ->with(['competition' => function ($query) {
                    $query->select(['id', 'title']);
                }]);
        }]);
    }

    /**
     * The userSubmissions that belong to the Competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userSubmissions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'submissions');
    }
}

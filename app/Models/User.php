<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp_created_at',
        'otp',
        'phone_number',
        'college',
        'identity',
        'sponsor_task',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_created_at',
        'otp'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_created_at' => 'datetime'
    ];

    /**
     * The competitions that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function competitions(): BelongsToMany
    {
        return $this->belongsToMany(Competition::class)
            ->withPivot(['team_code', 'created_at', 'allowed', 'id', 'team_size', 'team_name']);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get all of the societyCompetitions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function societyCompetitions(): HasManyThrough
    {
        return $this->hasManyThrough(Competition::class, SocietyUser::class, 'user_id', 'society_id', 'id', 'society_id')->with(['society', 'category']);
    }

    /**
     * Get all of the societyCompetitions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function competitionParticipations(): HasManyThrough
    {
        return $this->hasManyThrough(Competition::class, SocietyUser::class, 'user_id', 'society_id', 'id', 'society_id');
    }

    /**
     * The societies that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function societies(): BelongsToMany
    {
        return $this->belongsToMany(Society::class);
    }

    /**
     * The competitionSubmissions that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function competitionSubmissions(): BelongsToMany
    {
        return $this->belongsToMany(Competition::class, 'submissions');
    }
}

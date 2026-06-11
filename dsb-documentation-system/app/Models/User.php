<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    /**
     * Each user has one extended profile.
     * We use firstOrNew() via the accessor below so it's always
     * safe to call $user->profile->bio without a null check.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    /**
     * Always returns the profile model — creating an empty (unsaved)
     * instance if none exists yet. This prevents null errors in Blade.
     *
     * Usage: $user->getOrCreateProfile()
     */
    public function getOrCreateProfile(): UserProfile
    {
        return $this->profile ?? new UserProfile(['user_id' => $this->id]);
    }
}
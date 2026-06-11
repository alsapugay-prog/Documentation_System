<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     * All extended profile fields are fillable.
     */
    protected $fillable = [
        'user_id',
        'avatar',
        'contact_number',
        'address',
        'bio',
        'position',
        'department',
        'birthdate',
        'gender',
        'facebook',
        'linkedin',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'birthdate' => 'date',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    /**
     * A profile belongs to a single user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------
    // Accessors
    // -------------------------------------------------------

    /**
     * Returns the full public URL of the avatar image.
     * Falls back to a generated initials-based avatar URL when none is set.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Use UI Avatars as a sensible default (initials from the related user's name)
        $name = urlencode($this->user->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=2b5884&color=fff&size=200";
    }
}
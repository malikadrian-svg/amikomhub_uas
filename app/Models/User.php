<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'social_id',
        'social_provider',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relations ─────────────────────────────────────────────────────────────

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /** Events yang dibuat/dimiliki oleh organizer ini */
    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    // ─── Role Helper Methods ────────────────────────────────────────────────────

    /** Apakah user adalah Superadmin (admin pusat) */
    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /** Apakah user adalah Organizer (HIMA/Kepanitiaan) */
    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    /** Apakah user diizinkan masuk ke panel admin (superadmin atau organizer) */
    public function canAccessPanel(): bool
    {
        return in_array($this->role, ['superadmin', 'organizer']);
    }

    /** Inisial nama untuk avatar placeholder */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }
}

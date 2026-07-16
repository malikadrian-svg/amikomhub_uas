<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'organizer_id',
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'poster_path',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // ─── Relations ─────────────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /** Organizer (user) yang memiliki event ini */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    /** Hanya event yang sudah disetujui superadmin (tampil di homepage) */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /** Event yang menunggu persetujuan */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /** Event yang ditolak */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getAverageRatingAttribute()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : 0.0;
    }

    public function getRatingDistributionAttribute()
    {
        $total = $this->reviews()->count();
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $this->reviews()->where('rating', $i)->count();
            $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
            $distribution[$i] = [
                'count'      => $count,
                'percentage' => $percentage
            ];
        }
        return $distribution;
    }

    /** Label warna badge status untuk UI */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'approved' => [
                'label' => 'Approved',
                'bg'    => '#f0fdf4',
                'text'  => '#15803d',
                'border'=> '#bbf7d0',
            ],
            'pending'  => [
                'label' => 'Pending',
                'bg'    => '#fffbeb',
                'text'  => '#b45309',
                'border'=> '#fde68a',
            ],
            'rejected' => [
                'label' => 'Rejected',
                'bg'    => '#fff1f2',
                'text'  => '#be123c',
                'border'=> '#fecdd3',
            ],
            default    => [
                'label' => ucfirst($this->status ?? '-'),
                'bg'    => '#f8fafc',
                'text'  => '#475569',
                'border'=> '#e2e8f0',
            ],
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Business extends Model
{
    protected $table = 'businesses';

    protected $fillable = [
        'owner_user_id', 'nama', 'kategori', 'deskripsi', 'logo_path',
        'kontak_wa', 'kota', 'status', 'is_featured', 'featured_until',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'featured_until' => 'date',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeFeaturedFirst($query)
    {
        return $query->orderByRaw('(is_featured = 1 AND (featured_until IS NULL OR featured_until >= CURDATE())) DESC')
            ->orderByDesc('created_at');
    }
}

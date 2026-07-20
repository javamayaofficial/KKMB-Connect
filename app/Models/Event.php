<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'judul', 'deskripsi', 'poster_path', 'mulai_at', 'selesai_at',
        'lokasi', 'kuota', 'is_paid', 'harga', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'mulai_at' => 'datetime',
            'selesai_at' => 'datetime',
            'is_paid' => 'boolean',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function isFull(): bool
    {
        if (is_null($this->kuota)) {
            return false;
        }

        return $this->registrations()->where('status', '!=', 'cancelled')->count() >= $this->kuota;
    }

    public function sisaKuota(): ?int
    {
        if (is_null($this->kuota)) {
            return null;
        }

        return max(0, $this->kuota - $this->registrations()->where('status', '!=', 'cancelled')->count());
    }
}

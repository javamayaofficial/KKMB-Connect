<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MemberProfile extends Model
{
    protected $fillable = [
        'user_id', 'member_number', 'angkatan', 'profesi', 'bidang_usaha',
        'kota', 'negara', 'lat', 'lng', 'bio', 'foto_path', 'qr_token',
        'is_visible', 'verified_at', 'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'verified_at' => 'datetime',
            'lat' => 'float',
            'lng' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (MemberProfile $profile) {
            $profile->qr_token = $profile->qr_token ?: Str::random(40);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isVerified(): bool
    {
        return ! is_null($this->verified_at);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SubscriptionPlan extends Model
{
    protected $fillable = ['nama', 'slug', 'harga', 'durasi_hari', 'deskripsi_fitur', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::creating(function (SubscriptionPlan $plan) {
            $plan->slug = $plan->slug ?: Str::slug($plan->nama);
        });
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}

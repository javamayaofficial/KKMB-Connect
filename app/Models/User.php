<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'phone', 'password', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class, 'owner_user_id');
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function isActiveMember(): bool
    {
        return $this->status === 'active';
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->whereDate('berakhir_at', '>=', now())
            ->exists();
    }

    // Dipakai Filament untuk menentukan siapa yang boleh masuk panel admin.
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->hasAnyRole(['super_admin', 'pengurus']) && $this->status === 'active';
    }
}

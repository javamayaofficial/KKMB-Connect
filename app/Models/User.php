<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'phone', 'password', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'first_login_at' => 'datetime',
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

    public function onboardingChecklist(): array
    {
        $profile = $this->profile;

        return [
            'identitas' => filled($this->name) && filled($this->phone),
            'angkatan' => filled($profile?->angkatan),
            'domisili' => filled($profile?->kota),
            'profesional' => filled($profile?->profesi) || filled($profile?->bidang_usaha),
            'narasi' => filled($profile?->bio),
            'portofolio' => $this->businesses()->exists(),
        ];
    }

    public function onboardingCompletedCount(): int
    {
        return count(array_filter($this->onboardingChecklist()));
    }

    public function onboardingTotalCount(): int
    {
        return count($this->onboardingChecklist());
    }

    public function onboardingCompletionPercentage(): int
    {
        $total = $this->onboardingTotalCount();

        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->onboardingCompletedCount() / $total) * 100);
    }

    public function hasCompletedCoreProfile(): bool
    {
        $checklist = $this->onboardingChecklist();

        unset($checklist['portofolio']);

        return ! in_array(false, $checklist, true);
    }

    public function needsProfileCompletion(): bool
    {
        return ! $this->hasCompletedCoreProfile();
    }

    public function needsPortfolioCompletion(): bool
    {
        return ! $this->onboardingChecklist()['portofolio'];
    }

    public function hasCompletedOnboarding(): bool
    {
        return $this->hasCompletedCoreProfile() && ! $this->needsPortfolioCompletion();
    }

    public function requiresOnboardingCompletion(): bool
    {
        return ! $this->hasCompletedOnboarding();
    }

    public function onboardingRedirectRoute(): string
    {
        return $this->needsProfileCompletion() ? 'profile.edit' : 'business.create';
    }

    public function postOnboardingRoute(): string
    {
        return $this->isActiveMember() ? 'dashboard' : 'pending';
    }

    public function nextAppRoute(): string
    {
        return $this->requiresOnboardingCompletion()
            ? $this->onboardingRedirectRoute()
            : $this->postOnboardingRoute();
    }

    // Dipakai Filament untuk menentukan siapa yang boleh masuk panel admin.
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->status === 'active'
            && $this->roles()
                ->whereIn('name', ['super_admin', 'pengurus'])
                ->exists();
    }
}

<?php

namespace App\Support;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * "Temukan Relasi" — rekomendasi berbasis aturan (bukan LLM).
 * Skor kemiripan: bidang usaha sama (+3), profesi sama (+2), kota sama (+2), angkatan sama (+1).
 * Deterministik, cepat, dan aman untuk MVP. Fondasi untuk AI Assistant di fase 2.
 */
class RecommendationService
{
    public function forUser(User $user, int $limit = 10): Collection
    {
        $me = $user->profile;
        if (! $me) {
            return collect();
        }

        return MemberProfile::query()
            ->with('user')
            ->whereNotNull('verified_at')
            ->where('is_visible', true)
            ->where('user_id', '!=', $user->id)
            ->get()
            ->map(function (MemberProfile $p) use ($me) {
                $score = 0;
                if ($me->bidang_usaha && $p->bidang_usaha === $me->bidang_usaha) $score += 3;
                if ($me->profesi && $p->profesi === $me->profesi) $score += 2;
                if ($me->kota && $p->kota === $me->kota) $score += 2;
                if ($me->angkatan && $p->angkatan === $me->angkatan) $score += 1;
                $p->match_score = $score;
                return $p;
            })
            ->filter(fn ($p) => $p->match_score > 0)
            ->sortByDesc('match_score')
            ->take($limit)
            ->values();
    }
}

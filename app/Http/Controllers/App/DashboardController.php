<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MemberProfile;
use App\Models\User;
use App\Support\RecommendationService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(RecommendationService $rec)
    {
        $user = auth()->user();

        $stats = [
            'total_alumni' => MemberProfile::whereNotNull('verified_at')->count(),
            'total_kota' => MemberProfile::whereNotNull('verified_at')->distinct('kota')->count('kota'),
            'total_negara' => MemberProfile::whereNotNull('verified_at')->distinct('negara')->count('negara'),
        ];

        // Peta sebaran: agregasi jumlah alumni per kota (koordinat kota, bukan alamat presisi).
        $sebaran = MemberProfile::query()
            ->whereNotNull('verified_at')
            ->whereNotNull('lat')->whereNotNull('lng')
            ->select('kota', DB::raw('AVG(lat) as lat'), DB::raw('AVG(lng) as lng'), DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kota')
            ->get();

        $eventTerdekat = Event::published()
            ->where('mulai_at', '>=', now())
            ->orderBy('mulai_at')
            ->limit(5)
            ->get();

        $rekomendasi = $rec->forUser($user, 6);

        return view('app.dashboard', compact('stats', 'sebaran', 'eventTerdekat', 'rekomendasi'));
    }
}

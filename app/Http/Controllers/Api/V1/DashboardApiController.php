<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Support\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function stats()
    {
        return response()->json(['data' => [
            'total_alumni' => MemberProfile::whereNotNull('verified_at')->count(),
            'total_kota' => MemberProfile::whereNotNull('verified_at')->distinct('kota')->count('kota'),
        ]]);
    }

    public function map()
    {
        $sebaran = MemberProfile::query()
            ->whereNotNull('verified_at')->whereNotNull('lat')->whereNotNull('lng')
            ->select('kota', DB::raw('AVG(lat) as lat'), DB::raw('AVG(lng) as lng'), DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kota')->get();

        return response()->json(['data' => $sebaran]);
    }

    public function recommendations(Request $request, RecommendationService $rec)
    {
        return response()->json(['data' => $rec->forUser($request->user(), 10)]);
    }
}

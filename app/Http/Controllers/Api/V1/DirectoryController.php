<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\MemberProfile;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    public function alumni(Request $request)
    {
        $data = MemberProfile::query()
            ->with('user:id,name,email,phone')
            ->whereNotNull('verified_at')
            ->where('is_visible', true)
            ->when($request->q, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$request->q}%")))
            ->when($request->profesi, fn ($q) => $q->where('profesi', 'like', "%{$request->profesi}%"))
            ->when($request->bidang, fn ($q) => $q->where('bidang_usaha', 'like', "%{$request->bidang}%"))
            ->when($request->kota, fn ($q) => $q->where('kota', 'like', "%{$request->kota}%"))
            ->paginate(15);

        return response()->json($data);
    }

    public function alumniShow(MemberProfile $profile)
    {
        abort_unless($profile->isVerified() && $profile->is_visible, 404);
        return response()->json(['data' => $profile->load('user:id,name,email,phone')]);
    }

    public function businesses(Request $request)
    {
        $data = Business::query()
            ->with('owner:id,name')
            ->approved()
            ->when($request->q, fn ($q) => $q->where('nama', 'like', "%{$request->q}%"))
            ->when($request->kategori, fn ($q) => $q->where('kategori', 'like', "%{$request->kategori}%"))
            ->featuredFirst()
            ->paginate(15);

        return response()->json($data);
    }
}

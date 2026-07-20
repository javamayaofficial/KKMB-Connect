<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    // "Kelola Bisnis Saya"
    public function index()
    {
        $businesses = auth()->user()->businesses()->latest()->get();
        return view('app.business.index', compact('businesses'));
    }

    public function create()
    {
        return view('app.business.form', ['business' => new Business()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['owner_user_id'] = auth()->id();
        $data['status'] = 'pending'; // butuh persetujuan admin sebelum tampil publik
        $data['logo_path'] = $this->handleLogo($request, null);

        Business::create($data);

        $user = $request->user();

        return redirect()
            ->route($user->nextAppRoute())
            ->with('status', 'Portofolio berhasil diajukan. Profil anggota Anda kini lebih lengkap dan siap ditinjau.');
    }

    public function edit(Business $business)
    {
        $this->authorizeOwner($business);
        return view('app.business.form', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        $this->authorizeOwner($business);
        $data = $this->validated($request);
        $data['logo_path'] = $this->handleLogo($request, $business->logo_path);
        // Edit mengembalikan status ke pending agar admin meninjau ulang.
        $data['status'] = 'pending';
        $business->update($data);

        $user = $request->user();

        return redirect()
            ->route($user->nextAppRoute())
            ->with('status', 'Perubahan portofolio disimpan. Profil anggota Anda tetap tampil semakin kuat.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'kontak_wa' => ['nullable', 'string', 'max:30'],
            'kota' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);
    }

    protected function handleLogo(Request $request, ?string $current): ?string
    {
        if ($request->hasFile('logo')) {
            if ($current && Storage::disk('public')->exists($current)) {
                Storage::disk('public')->delete($current);
            }
            return $request->file('logo')->store('logos', 'public');
        }
        return $current;
    }

    protected function authorizeOwner(Business $business): void
    {
        abort_unless($business->owner_user_id === auth()->id(), 403);
    }
}

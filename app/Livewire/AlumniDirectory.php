<?php

namespace App\Livewire;

use App\Models\MemberProfile;
use Livewire\Component;
use Livewire\WithPagination;

class AlumniDirectory extends Component
{
    use WithPagination;

    public string $q = '';
    public string $profesi = '';
    public string $bidang = '';
    public string $kota = '';
    public string $angkatan = '';

    protected $queryString = ['q', 'profesi', 'bidang', 'kota', 'angkatan'];

    public function updating($name): void
    {
        // reset ke halaman 1 setiap filter berubah
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['q', 'profesi', 'bidang', 'kota', 'angkatan']);
        $this->resetPage();
    }

    public function render()
    {
        $alumni = MemberProfile::query()
            ->with('user')
            ->whereNotNull('verified_at')
            ->where('is_visible', true)
            ->when($this->q, fn ($query) => $query->whereHas('user',
                fn ($u) => $u->where('name', 'like', "%{$this->q}%")))
            ->when($this->profesi, fn ($query) => $query->where('profesi', 'like', "%{$this->profesi}%"))
            ->when($this->bidang, fn ($query) => $query->where('bidang_usaha', 'like', "%{$this->bidang}%"))
            ->when($this->kota, fn ($query) => $query->where('kota', 'like', "%{$this->kota}%"))
            ->when($this->angkatan, fn ($query) => $query->where('angkatan', $this->angkatan))
            ->latest()
            ->paginate(12);

        return view('livewire.alumni-directory', compact('alumni'));
    }
}

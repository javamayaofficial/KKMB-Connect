<?php

namespace App\Livewire;

use App\Models\Business;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessDirectory extends Component
{
    use WithPagination;

    public string $q = '';
    public string $kategori = '';
    public string $kota = '';

    protected $queryString = ['q', 'kategori', 'kota'];

    public function updating($name): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['q', 'kategori', 'kota']);
        $this->resetPage();
    }

    public function render()
    {
        $businesses = Business::query()
            ->with('owner')
            ->approved()
            ->when($this->q, fn ($query) => $query->where('nama', 'like', "%{$this->q}%"))
            ->when($this->kategori, fn ($query) => $query->where('kategori', 'like', "%{$this->kategori}%"))
            ->when($this->kota, fn ($query) => $query->where('kota', 'like', "%{$this->kota}%"))
            ->featuredFirst()
            ->paginate(12);

        return view('livewire.business-directory', compact('businesses'));
    }
}

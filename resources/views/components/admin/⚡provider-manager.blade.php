<?php

use App\Models\Provider;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new class extends Component {
    use WithFileUploads, WithPagination;

    // Form fields
    public $category_id, $name, $logo, $is_active = true, $providerId;
    public $search = '';

    // Fetch Providers with their Category relationship
    public function providers()
    {
        return Provider::with('category')
            ->where('name', 'like', "%{$this->search}%")
            ->latest()
            ->get();
    }

    // Fetch categories for the dropdown
    public function categories()
    {
        return Category::where('is_active', true)->get();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'category_id' => 'required|exists:categories,id',
            'logo' => $this->providerId ? 'nullable|image|max:1024' : 'required|image|max:1024',
        ]);

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
        ];

        if ($this->logo) {
            $data['logo'] = $this->logo->store('providers', 'public');
        }

        Provider::updateOrCreate(['id' => $this->providerId], $data);

        $this->dispatch('close-drawer');
        $this->reset(['name', 'category_id', 'logo', 'providerId']);
        $this->is_active = true;
    }

    public function resetForm()
    {
        $this->reset(['name', 'category_id', 'logo', 'providerId']);
        $this->is_active = true;
        $this->resetValidation();
    }

    // Update your edit method to reset validation first
    public function edit($id)
    {
        $this->resetValidation();
        $p = Provider::findOrFail($id);
        $this->providerId = $id;
        $this->name = $p->name;
        $this->category_id = $p->category_id;
        $this->is_active = $p->is_active;

        $this->dispatch('open-drawer');
    }

    public function delete($id)
    {
        Provider::find($id)->delete();
    }
}; ?>

<div>
    <x-slot:title>Providers</x-slot:title>

    <!-- Search Bar -->
    <div class="mb-4">
        <div class="input-group bg-white rounded-pill shadow-sm px-3 py-1">
            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" wire:model.live="search" class="form-control border-0 bg-transparent" placeholder="Search providers...">
        </div>
    </div>

    <!-- Provider Mobile List -->
    <div class="row g-3">
        @forelse($this->providers() as $provider)
        <div class="col-12" wire:key="{{ $provider->id }}">
            <div class="app-card shadow-sm d-flex align-items-center p-3 bg-white rounded-4">
                <div class="position-relative">
                    <img src="{{ asset('storage/'.$provider->logo) }}"
                        class="rounded-circle border object-fit-cover"
                        width="60" height="60">
                    @if($provider->is_active)
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                    @endif
                </div>

                <div class="ms-3 flex-grow-1">
                    <h6 class="mb-0 fw-bold text-dark">{{ $provider->name }}</h6>
                    <span class="badge bg-light text-primary border rounded-pill mt-1">
                        {{ $provider->category?->name ?? 'Uncategorized' }}
                    </span>
                </div>

                <div class="dropdown">
                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                        <li><a class="dropdown-item py-2" href="#" wire:click="edit({{ $provider->id }})"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item py-2 text-danger" href="#" wire:click="delete({{ $provider->id }})" wire:confirm="Are you sure?"><i class="bi bi-trash me-2"></i> Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-person-exclamation fs-1 text-muted"></i>
            <p class="mt-2 text-muted">No providers found.</p>
        </div>
        @endforelse
    </div>

    <!-- Mobile FAB (Add Provider) -->
    <button class="fab"
        style="bottom: 100px; right: 25px;"
        data-bs-toggle="offcanvas"
        data-bs-target="#providerDrawer"
        wire:click="resetForm"> <!-- Change this line -->
        <i class="bi bi-plus-lg fs-3"></i>
    </button>

    <!-- Bottom Sheet Form -->
    <div wire:ignore.self class="offcanvas offcanvas-bottom rounded-top-5" id="providerDrawer" tabindex="-1" style="height: 85%;">
        <div class="offcanvas-header border-bottom px-4">
            <h5 class="offcanvas-title fw-bold">{{ $providerId ? 'Edit' : 'New' }} Provider</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body px-4">
            <form wire:submit="save">
                <!-- Logo Upload -->
                <div class="mb-4 text-center">
                    <label class="d-block mb-3">
                        @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="rounded-circle border" width="100" height="100">
                        @else
                        <div class="mx-auto rounded-circle border d-flex align-items-center justify-content-center bg-light" style="width: 100px; height: 100px;">
                            <i class="bi bi-camera fs-2 text-muted"></i>
                        </div>
                        @endif
                        <input type="file" wire:model="logo" class="d-none">
                        <div class="mt-2 text-primary small fw-bold">Tap to upload logo</div>
                    </label>
                    @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <!-- Name -->
                <div class="form-floating mb-3">
                    <input type="text" wire:model="name" class="form-control rounded-3" id="pName" placeholder="Provider Name">
                    <label for="pName">Provider Name</label>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Category Select -->
                <div class="form-floating mb-3">
                    <select wire:model="category_id" class="form-select rounded-3" id="pCat">
                        <option value="">Choose Category</option>
                        @foreach($this->categories() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label for="pCat">Category</label>
                    @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Toggle Status -->
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 mb-4">
                    <span class="fw-bold">Active Status</span>
                    <div class="form-check form-switch p-0 m-0">
                        <input class="form-check-input ms-0" type="checkbox" wire:model="is_active" style="width: 45px; height: 24px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-sm">
                    <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                    {{ $providerId ? 'Update Provider' : 'Create Provider' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts to handle Drawer -->
    <script>
        window.addEventListener('close-drawer', () => {
            const drawerElement = document.getElementById('providerDrawer');
            const instance = bootstrap.Offcanvas.getInstance(drawerElement) || new bootstrap.Offcanvas(drawerElement);
            instance.hide();
        });
        window.addEventListener('open-drawer', () => {
            const drawerElement = document.getElementById('providerDrawer');
            const instance = bootstrap.Offcanvas.getInstance(drawerElement) || new bootstrap.Offcanvas(drawerElement);
            instance.show();
        });
    </script>
</div>
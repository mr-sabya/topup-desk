<?php

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public $name, $slug, $icon, $is_active = true, $categoryId;
    public $search = '';

    public function categories()
    {
        return Category::where('name', 'like', "%{$this->search}%")->latest()->get();
    }

    public function updatedName($v)
    {
        $this->slug = Str::slug($v);
    }

    public function save()
    {
        $this->validate(['name' => 'required', 'slug' => 'required|unique:categories,slug,' . $this->categoryId]);

        $data = ['name' => $this->name, 'slug' => $this->slug, 'is_active' => $this->is_active];
        if ($this->icon) $data['icon'] = $this->icon->store('categories', 'public');

        Category::updateOrCreate(['id' => $this->categoryId], $data);
        $this->dispatch('close-drawer');
        $this->reset();
    }

    public function edit($id)
    {
        $cat = Category::find($id);
        $this->categoryId = $id;
        $this->name = $cat->name;
        $this->slug = $cat->slug;
        $this->dispatch('open-drawer');
    }
}; ?>

<div>
    <x-slot:title>Categories</x-slot:title>

    <!-- Search Bar -->
    <div class="mb-3">
        <div class="input-group bg-white rounded-3 shadow-sm">
            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
            <input type="text" wire:model.live="search" class="form-control border-0 ps-0" placeholder="Search categories...">
        </div>
    </div>

    <!-- Mobile List -->
    @foreach($this->categories() as $cat)
    <div class="app-card shadow-sm" wire:key="{{ $cat->id }}">
        <img src="{{ asset('storage/'.$cat->icon) }}" class="rounded-3 me-3" width="50" height="50">
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold">{{ $cat->name }}</h6>
            <small class="text-muted">{{ $cat->slug }}</small>
        </div>
        <div class="text-end">
            <button wire:click="edit({{ $cat->id }})" class="btn btn-sm text-primary">Edit</button>
        </div>
    </div>
    @endforeach

    <!-- FAB Button (Add) -->
    <button class="fab" data-bs-toggle="offcanvas" data-bs-target="#formDrawer">
        <i class="bi bi-plus-lg fs-4"></i>
    </button>

    <!-- Bottom Sheet Form -->
    <div wire:ignore.self class="offcanvas offcanvas-bottom" id="formDrawer" tabindex="-1">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">{{ $categoryId ? 'Edit' : 'New' }} Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form wire:submit="save">
                <div class="mb-3 text-center">
                    @if ($icon)
                    <img src="{{ $icon->temporaryUrl() }}" class="rounded-circle mb-2" width="80">
                    @endif
                    <input type="file" wire:model="icon" class="form-control form-control-sm">
                </div>
                <div class="form-floating mb-3">
                    <input type="text" wire:model.live="name" class="form-control" id="n" placeholder="Name">
                    <label for="n">Category Name</label>
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" wire:model="is_active" id="active">
                    <label class="form-check-label" for="active">Enable Category</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('close-drawer', () => {
            const drawer = document.querySelector('#formDrawer');
            bootstrap.Offcanvas.getInstance(drawer).hide();
        });
        window.addEventListener('open-drawer', () => {
            new bootstrap.Offcanvas(document.querySelector('#formDrawer')).show();
        });
    </script>
</div>
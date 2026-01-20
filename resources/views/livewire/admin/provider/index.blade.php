<div>
    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold m-0">Providers</h4>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 small py-2 mb-3">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-4 small py-2 mb-3">
        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Search Bar -->
    <div class="mb-3">
        <div class="input-group bg-white rounded-3 shadow-sm border">
            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" wire:model.live="search" class="form-control border-0 ps-0" placeholder="Search providers...">
        </div>
    </div>

    <!-- Provider List -->
    <div class="row g-2">
        @forelse($this->providers as $provider)
        <div class="col-12" wire:key="provider-{{ $provider->id }}">
            <div class="app-card bg-white p-3 shadow-sm d-flex align-items-center rounded-4 border-0">
                <div class="position-relative">
                    <img src="{{ asset('storage/'.$provider->logo) }}" class="rounded-circle border object-fit-cover" width="55" height="55">
                    @if($provider->is_active)
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle"></span>
                    @endif
                </div>

                <div class="ms-3 flex-grow-1">
                    <h6 class="mb-0 fw-bold text-dark">{{ $provider->name }}</h6>
                    <span class="badge bg-light text-primary border rounded-pill mt-1" style="font-size: 0.7rem;">
                        {{ $provider->category?->name ?? 'Uncategorized' }}
                    </span>
                </div>

                <div class="text-end d-flex gap-1">
                    <button wire:click="edit({{ $provider->id }})" class="btn btn-light rounded-pill btn-sm px-3 border">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button wire:click="confirmDelete({{ $provider->id }})" class="btn btn-light rounded-pill btn-sm px-3 border text-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-person-exclamation fs-1 text-muted opacity-25"></i>
            <p class="mt-2 text-muted">No providers found.</p>
        </div>
        @endforelse
    </div>

    <!-- FAB Button -->
    <button class="fab btn btn-primary shadow-lg d-flex align-items-center justify-content-center"
        style="position: fixed; bottom: 90px; right: 20px; width: 56px; height: 56px; border-radius: 50%; z-index: 1000;"
        data-bs-toggle="offcanvas" data-bs-target="#providerDrawer" wire:click="resetForm">
        <i class="bi bi-plus-lg fs-4"></i>
    </button>

    <!-- Bottom Sheet Form (Offcanvas) -->
    <div wire:ignore.self class="offcanvas offcanvas-bottom rounded-top-5" id="providerDrawer" tabindex="-1" style="height: 85%;">
        <div class="offcanvas-header border-bottom px-4">
            <h5 class="offcanvas-title fw-bold">{{ $providerId ? 'Edit' : 'New' }} Provider</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body px-4 bg-light">
            <form wire:submit.prevent="save">
                <!-- Logo Upload -->
                <div class="mb-4 text-center">
                    <label class="d-block mb-2" style="cursor: pointer;">
                        @if ($logo && !is_string($logo))
                        <img src="{{ $logo->temporaryUrl() }}" class="rounded-circle border shadow-sm" width="100" height="100" style="object-fit: cover;">
                        @elseif($providerId && \App\Models\Provider::find($providerId)->logo)
                        <img src="{{ asset('storage/'.\App\Models\Provider::find($providerId)->logo) }}" class="rounded-circle border shadow-sm" width="100" height="100" style="object-fit: cover;">
                        @else
                        <div class="mx-auto rounded-circle border d-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 100px; height: 100px;">
                            <i class="bi bi-camera fs-2 text-muted"></i>
                        </div>
                        @endif
                        <input type="file" wire:model="logo" class="d-none">
                        <div class="mt-2 text-primary small fw-bold">Change Logo</div>
                        <div wire:loading wire:target="logo" class="text-muted small">Uploading...</div>
                    </label>
                    @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <!-- Provider Name -->
                <div class="form-floating mb-3">
                    <input type="text" wire:model="name" class="form-control rounded-3" id="pName" placeholder="Name">
                    <label for="pName">Provider Name</label>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Category Select -->
                <div class="form-floating mb-3">
                    <select wire:model="category_id" class="form-select rounded-3" id="pCat">
                        <option value="">Choose Category</option>
                        @foreach($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label for="pCat">Category</label>
                    @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Status Toggle -->
                <div class="form-check form-switch mb-4 p-3 bg-white border rounded-3 d-flex justify-content-between align-items-center">
                    <label class="form-check-label fw-bold" for="active">Active Status</label>
                    <input class="form-check-input ms-0" type="checkbox" wire:model="is_active" id="active" style="width: 45px; height: 24px;">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                    <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-2"></span>
                    {{ $providerId ? 'Update Provider' : 'Create Provider' }}
                </button>
            </form>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-body text-center p-4">
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold">Remove Provider?</h5>
                    <p class="text-muted small">Are you sure? All settings for this provider will be lost.</p>

                    <div class="d-grid gap-2">
                        <button type="button" wire:click="delete" class="btn btn-danger py-2 rounded-pill fw-bold">
                            <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm me-2"></span>
                            Yes, Delete
                        </button>
                        <button type="button" class="btn btn-light py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rounded-top-5 {
            border-top-left-radius: 2.5rem !important;
            border-top-right-radius: 2.5rem !important;
        }

        .object-fit-cover {
            object-fit: cover;
        }
    </style>

    <script>
        window.addEventListener('close-drawer', () => {
            bootstrap.Offcanvas.getOrCreateInstance(document.querySelector('#providerDrawer')).hide();
        });
        window.addEventListener('open-drawer', () => {
            bootstrap.Offcanvas.getOrCreateInstance(document.querySelector('#providerDrawer')).show();
        });
        window.addEventListener('open-delete-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.querySelector('#deleteModal')).show();
        });
        window.addEventListener('close-delete-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.querySelector('#deleteModal')).hide();
        });
    </script>
</div>
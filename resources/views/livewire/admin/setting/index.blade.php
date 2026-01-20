<div>
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <h4 class="fw-bold m-0 text-dark">App Settings</h4>
    </div>

    @if (session()->has('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 small py-2 mb-4 animate__animated animate__fadeIn">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    </div>
    @endif

    <form wire:submit.prevent="save">
        <!-- 1. Branding Section -->
        <div class="app-card bg-white p-4 shadow-sm rounded-4 border-0 mb-3 text-center">
            <label class="d-block mb-3" style="cursor: pointer;">
                @if ($new_logo)
                <img src="{{ $new_logo->temporaryUrl() }}" class="rounded-3 border shadow-sm" width="120" height="120" style="object-fit: contain;">
                @elseif($site_logo)
                <img src="{{ asset('storage/'.$site_logo) }}" class="rounded-3 border shadow-sm" width="120" height="120" style="object-fit: contain;">
                @else
                <div class="mx-auto rounded-3 border d-flex align-items-center justify-content-center bg-light" style="width: 120px; height: 120px;">
                    <i class="bi bi-image fs-1 text-muted"></i>
                </div>
                @endif
                <input type="file" wire:model="new_logo" class="d-none">
                <div class="mt-2 text-primary small fw-bold"><i class="bi bi-camera me-1"></i> Change Site Logo</div>
                <div wire:loading wire:target="new_logo" class="text-muted small mt-1">Uploading...</div>
            </label>
            @error('new_logo') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <!-- 2. General Information Section (Continued) -->
        <div class="app-card bg-white p-4 shadow-sm rounded-4 border-0 mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>General Info</h6>

            <div class="form-floating mb-3">
                <input type="text" wire:model="site_name" class="form-control rounded-3 border-light bg-light" id="siteName" placeholder="Site Name">
                <label for="siteName">Site Name</label>
                @error('site_name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="email" wire:model="site_email" class="form-control rounded-3 border-light bg-light" id="siteEmail" placeholder="Email Address">
                <label for="siteEmail">Support Email</label>
                @error('site_email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="tel" wire:model="site_phone" class="form-control rounded-3 border-light bg-light" id="sitePhone" placeholder="Phone Number">
                <label for="sitePhone">Contact Phone</label>
                @error('site_phone') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- 3. Features Section (Placeholder for future settings) -->
        <div class="app-card bg-white p-4 shadow-sm rounded-4 border-0 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i>Preferences</h6>

            <div class="d-flex justify-content-between align-items-center py-2">
                <div>
                    <h6 class="mb-0 fw-600 small">Maintenance Mode</h6>
                    <small class="text-muted">Disable public access to the shop</small>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" style="width: 40px; height: 20px;">
                </div>
            </div>
        </div>

        <!-- Submit Button (Fixed at bottom for Mobile UX) -->
        <div class=" p-3 bg-white border-top shadow-lg d-md-none" style="z-index: 1040;">
            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-2"></span>
                Save All Changes
            </button>
        </div>

        <!-- Submit Button (Standard for Desktop) -->
        <div class="d-none d-md-block mb-5">
            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-2"></span>
                Save Settings
            </button>
        </div>
    </form>

    <style>
        .fw-600 {
            font-weight: 600;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        /* Add padding to the bottom so the content isn't hidden by the fixed button on mobile */
        @media (max-width: 767px) {
            body {
                padding-bottom: 100px;
            }
        }
    </style>
</div>
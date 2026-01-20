<div>
    <div class="mb-4">
        <h4 class="fw-bold m-0">My Profile</h4>
        <p class="text-muted small">Manage your account settings and security</p>
    </div>

    <!-- Section 1: Profile Information -->
    <div class="app-card bg-white p-4 shadow-sm rounded-4 border-0 mb-4">
        <h6 class="fw-bold mb-4 text-primary"><i class="bi bi-person-circle me-2"></i>Personal Info</h6>

        @if (session()->has('success'))
        <div class="alert alert-success border-0 rounded-4 small py-2 mb-3">{{ session('success') }}</div>
        @endif

        <form wire:submit.prevent="updateProfile">
            <!-- Avatar Upload -->
            <div class="text-center mb-4">
                <label class="position-relative d-inline-block" style="cursor: pointer;">
                    @if ($new_avatar)
                    <img src="{{ $new_avatar->temporaryUrl() }}" class="rounded-circle border shadow-sm" width="100" height="100" style="object-fit: cover;">
                    @elseif($avatar)
                    <img src="{{ asset('storage/'.$avatar) }}" class="rounded-circle border shadow-sm" width="100" height="100" style="object-fit: cover;">
                    @else
                    <div class="mx-auto rounded-circle border d-flex align-items-center justify-content-center bg-light" style="width: 100px; height: 100px;">
                        <i class="bi bi-person fs-1 text-muted"></i>
                    </div>
                    @endif
                    <input type="file" wire:model="new_avatar" class="d-none">
                    <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1 border border-2 border-white" style="width: 30px; height: 30px;">
                        <i class="bi bi-camera-fill small"></i>
                    </div>
                </label>
                <div wire:loading wire:target="new_avatar" class="text-primary small d-block mt-1">Uploading...</div>
                @error('new_avatar') <div class="text-danger x-small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="text" wire:model="name" class="form-control rounded-3 border-light bg-light" id="userName" placeholder="Name">
                <label for="userName">Full Name</label>
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-floating mb-4">
                <input type="email" wire:model="email" class="form-control rounded-3 border-light bg-light" id="userEmail" placeholder="Email">
                <label for="userEmail">Email Address</label>
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                <span wire:loading wire:target="updateProfile" class="spinner-border spinner-border-sm me-2"></span>
                Save Profile Changes
            </button>
        </form>
    </div>

    <!-- Section 2: Security / Password -->
    <div class="app-card bg-white p-4 shadow-sm rounded-4 border-0 mb-5">
        <h6 class="fw-bold mb-4 text-danger"><i class="bi bi-shield-lock me-2"></i>Security</h6>

        @if (session()->has('password_success'))
        <div class="alert alert-success border-0 rounded-4 small py-2 mb-3">{{ session('password_success') }}</div>
        @endif

        <form wire:submit.prevent="updatePassword">
            <div class="form-floating mb-3">
                <input type="password" wire:model="current_password" class="form-control rounded-3 border-light bg-light" id="currPwd" placeholder="Current Password">
                <label for="currPwd">Current Password</label>
                @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="password" wire:model="password" class="form-control rounded-3 border-light bg-light" id="newPwd" placeholder="New Password">
                <label for="newPwd">New Password</label>
                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-floating mb-4">
                <input type="password" wire:model="password_confirmation" class="form-control rounded-3 border-light bg-light" id="confPwd" placeholder="Confirm Password">
                <label for="confPwd">Confirm New Password</label>
            </div>

            <button type="submit" class="btn btn-outline-danger w-100 py-3 rounded-pill fw-bold border-2">
                <span wire:loading wire:target="updatePassword" class="spinner-border spinner-border-sm me-2"></span>
                Change Password
            </button>
        </form>
    </div>

    <style>
        .bg-light {
            background-color: #f8f9fa !important;
        }

        .form-control:focus {
            background-color: #fff !important;
            border-color: #0d6efd !important;
            box-shadow: none;
        }
    </style>
</div>
<div class="row justify-content-center align-items-center vh-100">
    <div class="col-11 col-md-4">
        <div class="card border-0 shadow-lg rounded-4 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Admin Login</h3>
                <p class="text-muted small">Enter your credentials to manage the app</p>
            </div>

            <form wire:submit.prevent="login">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Email Address</label>
                    <input type="email" wire:model="email" class="form-control py-2 shadow-sm rounded-3 border-light" placeholder="admin@example.com">
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <input type="password" wire:model="password" class="form-control py-2 shadow-sm rounded-3 border-light" placeholder="••••••••">
                    @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" wire:model="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                    <span wire:loading class="spinner-border spinner-border-sm me-2" role="status"></span>
                    <span wire:loading.remove>Sign In</span>
                    <span wire:loading>Authenticating...</span>
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="/" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Back to Homepage</a>
        </div>
    </div>
</div>
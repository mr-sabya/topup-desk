<?php
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $email = '', $password = '', $remember = false;

    public function login()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/admin');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }
}; ?>

<div class="row justify-content-center">
    <div class="col-11 col-md-4">
        <div class="card border-0 shadow-lg rounded-4 p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Admin Login</h3>
                <p class="text-muted small">Enter your credentials to manage app</p>
            </div>

            <form wire:submit="login">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Address</label>
                    <input type="email" wire:model="email" class="form-control py-2 shadow-sm" placeholder="admin@example.com">
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" wire:model="password" class="form-control py-2 shadow-sm" placeholder="••••••••">
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" wire:model="remember" id="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow">
                    <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                    Sign In
                </button>
            </form>
        </div>
    </div>
</div>
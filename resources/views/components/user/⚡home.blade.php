<?php

use App\Models\Category;
use App\Models\Provider;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.user')] class extends Component {
    public $step = 1;

    // Selections
    public $selectedCategory = null;
    public $selectedProvider = null;

    // Form inputs
    public $phone_number, $account_number, $amount, $connection_type = 'Prepaid', $guest_email;

    // Data fetching methods
    public function categories()
    {
        return Category::where('is_active', true)->get();
    }

    public function providers()
    {
        return $this->selectedCategory
            ? Provider::where('category_id', $this->selectedCategory->id)->where('is_active', true)->get()
            : [];
    }

    // Navigation logic
    public function selectCategory($id)
    {
        $this->selectedCategory = Category::find($id);
        $this->step = 2;
    }

    public function selectProvider($id)
    {
        $this->selectedProvider = Provider::find($id);
        $this->step = 3;
    }

    public function goBack()
    {
        if ($this->step > 1) {
            $this->step--;
            if ($this->step == 1) $this->selectedCategory = null;
            if ($this->step == 2) $this->selectedProvider = null;
        }
    }

    public function submit()
    {
        $this->validate([
            'phone_number' => 'required|numeric|digits_between:10,15',
            'amount' => 'required|numeric|min:10',
        ]);

        Transaction::create([
            'category_id' => $this->selectedCategory->id,
            'provider_id' => $this->selectedProvider->id,
            'phone_number' => $this->phone_number,
            'amount' => $this->amount,
            'connection_type' => $this->connection_type,
            'status' => 'pending',
            'trx_id' => 'TXN' . rand(100000, 999999),
        ]);

        session()->flash('success', 'Request sent! We are processing your payment.');
        $this->reset(['step', 'selectedCategory', 'selectedProvider', 'phone_number', 'amount']);
    }
}; ?>

<div>
    <!-- Back Navigation Overlay -->
    @if($step > 1)
    <div class="mb-3 animate__animated animate__fadeIn">
        <button wire:click="goBack" class="btn btn-light rounded-pill px-3 btn-sm border-0 shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </button>
    </div>
    @endif

    <!-- STEP 1: CATEGORY SELECTION -->
    @if($step == 1)
    <div class="animate__animated animate__fadeInRight">
        <h4 class="fw-bold mb-1">Services</h4>
        <p class="text-muted small mb-4">Select what you want to pay for</p>

        <div class="row g-3">
            @foreach($this->categories() as $cat)
            <div class="col-md-2 col-6 text-center">
                <div wire:click="selectCategory({{ $cat->id }})" class="p-3 card-app active-scale d-flex flex-column align-items-center justify-content-center" style="aspect-ratio: 1/1;">
                    <div class="bg-indigo-soft rounded-circle mb-2" style="background: #eef2ff;">
                        <img src="{{ asset('storage/'.$cat->icon) }}" style="width: 80px; height: 80px;">
                    </div>
                    <span class="fw-600" style="font-size: 14px; color: #1e293b;">{{ $cat->name }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- STEP 2: PROVIDER SELECTION -->
    @if($step == 2)
    <div class="animate__animated animate__fadeInRight">
        <h4 class="fw-bold mb-1">Operator</h4>
        <p class="text-muted small mb-4">Choose your provider for {{ $selectedCategory->name }}</p>

        <div class="row g-2">
            @foreach($this->providers() as $prov)
            <div class="col-12">
                <div wire:click="selectProvider({{ $prov->id }})" class="card-app p-3 d-flex align-items-center active-scale">
                    <img src="{{ asset('storage/'.$prov->logo) }}" class="rounded-circle border" width="45" height="45">
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold">{{ $prov->name }}</h6>
                        <small class="text-muted">Instant Payment</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- STEP 3: TRANSACTION FORM -->
    @if($step == 3)
    <div class="animate__animated animate__fadeInRight">
        <div class="card-app p-4 shadow-sm mb-4">
            <div class="text-center mb-4">
                <img src="{{ asset('storage/'.$selectedProvider->logo) }}" class="rounded-circle border mb-2" width="60" height="60">
                <h5 class="fw-bold mb-0 text-dark">{{ $selectedProvider->name }}</h5>
                <small class="text-muted">{{ $selectedCategory->name }} Service</small>
            </div>

            @if (session()->has('success'))
            <div class="alert alert-success rounded-4 small py-2 mb-3">{{ session('success') }}</div>
            @endif

            <form wire:submit="submit">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Phone Number</label>
                    <input type="tel" wire:model="phone_number" class="form-control form-control-lg" placeholder="01XXXXXXXXX">
                    @error('phone_number') <span class="text-danger x-small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Amount</label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light fw-bold">৳</span>
                        <input type="number" wire:model="amount" class="form-control form-control-lg border-start-0" placeholder="0.00">
                    </div>
                    @error('amount') <span class="text-danger x-small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Type</label>
                    <div class="d-flex gap-2">
                        <button type="button" wire:click="$set('connection_type', 'Prepaid')"
                            class="btn flex-grow-1 rounded-pill py-2 {{ $connection_type == 'Prepaid' ? 'btn-primary' : 'btn-light' }}">Prepaid</button>
                        <button type="button" wire:click="$set('connection_type', 'Postpaid')"
                            class="btn flex-grow-1 rounded-pill py-2 {{ $connection_type == 'Postpaid' ? 'btn-primary' : 'btn-light' }}">Postpaid</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 shadow-lg">
                    <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                    Pay Now
                </button>
            </form>
        </div>
    </div>
    @endif

    <style>
        .active-scale:active {
            transform: scale(0.96);
            transition: 0.1s;
        }

        .fw-600 {
            font-weight: 600;
        }

        .x-small {
            font-size: 11px;
        }

        /* Simple animations */
        .animate__animated {
            animation-duration: 0.4s;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate__fadeInRight {
            animation-name: fadeInRight;
        }
    </style>
</div>
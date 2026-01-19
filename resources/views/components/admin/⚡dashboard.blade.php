<?php

use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {

    public $filterStatus = '';
    public $search = ''; // New: Search property
    public $selectedTransaction = null;

    public function getStatsProperty()
    {
        return [
            'total_volume' => Transaction::where('status', 'success')->sum('amount'),
            'pending_count' => Transaction::where('status', 'pending')->count(),
        ];
    }

    public function transactions()
    {
        return Transaction::with(['category', 'provider'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            // New: Search by phone or account number
            ->when($this->search, function ($q) {
                $q->where('phone_number', 'like', "%{$this->search}%")
                    ->orWhere('account_number', 'like', "%{$this->search}%")
                    ->orWhere('trx_id', 'like', "%{$this->search}%");
            })
            ->latest()
            ->take(25)
            ->get();
    }

    public function showDetails($id)
    {
        $this->selectedTransaction = Transaction::with(['category', 'provider'])->find($id);
        $this->dispatch('open-details');
    }

    public function updateStatus($id, $status)
    {
        Transaction::where('id', $id)->update(['status' => $status]);
        $this->selectedTransaction = Transaction::find($id);
    }
}; ?>

<div>
    <x-slot:title>Dashboard</x-slot:title>

    <!-- 1. Quick Stats -->
    <div class="row g-2 mb-3">
        <div class="col-7">
            <div class="app-card bg-primary text-white p-3 shadow-sm border-0 rounded-4">
                <small class="opacity-75">Success Volume</small>
                <h4 class="fw-bold mb-0">৳{{ number_format($this->stats['total_volume'], 0) }}</h4>
            </div>
        </div>
        <div class="col-5">
            <div class="app-card bg-white p-3 shadow-sm border-0 rounded-4">
                <small class="text-muted">Pending</small>
                <h4 class="fw-bold mb-0 text-warning">{{ $this->stats['pending_count'] }}</h4>
            </div>
        </div>
    </div>

    <!-- 2. Search Bar (Mobile Style) -->
    <div class="sticky-top bg-f2f2f7 pt-2 pb-3" style="top: 55px; z-index: 10;">
        <div class="input-group bg-white rounded-pill shadow-sm px-3 py-1">
            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" wire:model.live.debounce.300ms="search"
                class="form-control border-0 bg-transparent"
                placeholder="Search Phone or Account...">
            @if($search)
            <button class="btn btn-link text-muted" wire:click="$set('search', '')">
                <i class="bi bi-x-circle-fill"></i>
            </button>
            @endif
        </div>
    </div>

    <!-- 3. Status Filters -->
    <div class="d-flex bg-white rounded-pill p-1 mb-4 shadow-sm border">
        <button wire:click="$set('filterStatus', '')" class="btn flex-grow-1 rounded-pill {{ $filterStatus === '' ? 'btn-dark' : 'btn-light border-0' }} py-2 small">All</button>
        <button wire:click="$set('filterStatus', 'pending')" class="btn flex-grow-1 rounded-pill {{ $filterStatus === 'pending' ? 'btn-warning text-white' : 'btn-light border-0' }} py-2 small">Pending</button>
        <button wire:click="$set('filterStatus', 'success')" class="btn flex-grow-1 rounded-pill {{ $filterStatus === 'success' ? 'btn-success' : 'btn-light border-0' }} py-2 small">Success</button>
    </div>

    <!-- 4. Transactions List -->
    @forelse($this->transactions() as $trx)
    <div wire:click="showDetails({{ $trx->id }})"
        class="app-card mb-2 p-3 bg-white shadow-sm d-flex align-items-center rounded-4 border-0 active-scale shadow-hover">

        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width: 48px; height: 48px; background: {{ $trx->status == 'success' ? '#e8f5e9' : ($trx->status == 'pending' ? '#fff3e0' : '#f5f5f5') }}">
            <img src="{{ asset('storage/'.$trx->provider?->logo) }}" class="rounded-circle" width="30" height="30">
        </div>

        <div class="flex-grow-1">
            <div class="d-flex justify-content-between">
                <h6 class="mb-0 fw-bold text-dark">{{ $trx->provider->name }}</h6>
                <span class="fw-bold text-dark">৳{{ number_format($trx->amount, 0) }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <span class="text-secondary small">{{ $trx->phone_number ?? $trx->account_number }}</span>
                <span class="text-muted small" style="font-size: 0.65rem;">{{ $trx->created_at->format('h:i A') }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-search fs-1 text-muted opacity-25"></i>
        <p class="text-muted mt-2">No matches found</p>
    </div>
    @endforelse

    <!-- 5. Detail Bottom Sheet with Copy Function -->
    <div>
        <div wire:ignore.self class="offcanvas offcanvas-bottom rounded-top-5" id="detailsDrawer" tabindex="-1" style="height: 75%;">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body bg-light">
                @if($selectedTransaction)
                <div class="app-card bg-white p-4 text-center rounded-4 mb-3 border shadow-sm">
                    <small class="text-muted d-block mb-1">Total Amount</small>
                    <h1 class="fw-bold mb-0 text-dark">৳{{ number_format($selectedTransaction->amount, 2) }}</h1>
                    <div class="mt-2">
                        <span class="badge {{ $selectedTransaction->status == 'success' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3">
                            {{ strtoupper($selectedTransaction->status) }}
                        </span>
                    </div>
                </div>

                <div class="list-group rounded-4 shadow-sm border-0 mb-4">
                    <!-- COPYABLE PHONE/ACCOUNT NUMBER -->
                    @php $targetNumber = $selectedTransaction->phone_number ?? $selectedTransaction->account_number; @endphp
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-0">
                        <div>
                            <small class="text-muted d-block">Number to Recharge/Pay</small>
                            <span class="fw-bold fs-5 text-primary">{{ $targetNumber }}</span>
                        </div>
                        <!-- Alpine.js Copy Logic -->
                        <div x-data="{ copied: false }">
                            <button @click="navigator.clipboard.writeText('{{ $targetNumber }}'); 
                                            copied = true; 
                                            setTimeout(() => copied = false, 2000)"
                                class="btn rounded-pill px-3 fw-bold"
                                :class="copied ? 'btn-success' : 'btn-outline-primary'">
                                <span x-show="!copied"><i class="bi bi-clipboard"></i> Copy</span>
                                <span x-show="copied"><i class="bi bi-check-lg"></i> Copied</span>
                            </button>
                        </div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between py-3 border-top">
                        <span class="text-muted">Provider</span>
                        <span class="fw-bold">{{ $selectedTransaction->provider->name }}</span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between py-3 border-top">
                        <span class="text-muted">Type</span>
                        <span class="badge bg-secondary rounded-pill">{{ $selectedTransaction->connection_type ?? 'Prepaid' }}</span>
                    </div>

                    @if($selectedTransaction->trx_id)
                    <div class="list-group-item d-flex justify-content-between py-3 border-top">
                        <span class="text-muted">TRX ID</span>
                        <span class="fw-bold text-uppercase small">{{ $selectedTransaction->trx_id }}</span>
                    </div>
                    @endif
                </div>

                <!-- Admin Action Buttons -->
                @if($selectedTransaction->status === 'pending')
                <div class="row g-2">
                    <div class="col-6">
                        <button wire:click="updateStatus({{ $selectedTransaction->id }}, 'failed')"
                            class="btn btn-light w-100 py-3 rounded-4 fw-bold text-danger border">Reject</button>
                    </div>
                    <div class="col-6">
                        <button wire:click="updateStatus({{ $selectedTransaction->id }}, 'success')"
                            class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow">Complete</button>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f2f2f7 !important;
        }

        .bg-f2f2f7 {
            background-color: #f2f2f7;
        }

        .rounded-top-5 {
            border-top-left-radius: 2.5rem !important;
            border-top-right-radius: 2.5rem !important;
        }

        .active-scale:active {
            transform: scale(0.96);
            transition: 0.1s;
        }
    </style>

    <script>
        window.addEventListener('open-details', () => {
            const drawer = document.getElementById('detailsDrawer');
            new bootstrap.Offcanvas(drawer).show();
        });
    </script>
</div>
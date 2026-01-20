<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $filterStatus = '';

    #[Url]
    public $search = '';

    public $selectedTransactionId = null;

    /**
     * Stats calculated via Computed Property for performance.
     * In the view, access as $this->stats
     */
    #[Computed]
    public function stats()
    {
        return [
            'total_volume' => Transaction::where('status', 'success')->sum('amount'),
            'pending_count' => Transaction::where('status', 'pending')->count(),
        ];
    }

    /**
     * Get transactions based on filters and search.
     * In the view, access as $this->transactions
     */
    #[Computed]
    public function transactions()
    {
        return Transaction::with(['category', 'provider'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('phone_number', 'like', "%{$this->search}%")
                        ->orWhere('account_number', 'like', "%{$this->search}%")
                        ->orWhere('trx_id', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->take(25)
            ->get();
    }

    /**
     * Used to fetch the single transaction for the detail drawer.
     */
    #[Computed]
    public function selectedTransaction()
    {
        return $this->selectedTransactionId
            ? Transaction::with(['category', 'provider'])->find($this->selectedTransactionId)
            : null;
    }

    public function showDetails($id)
    {
        $this->selectedTransactionId = $id;
        $this->dispatch('open-details');
    }

    public function updateStatus($id, $status)
    {
        Transaction::where('id', $id)->update(['status' => $status]);

        // Refresh the computed property data
        unset($this->selectedTransaction);

        session()->flash('status_updated', 'Transaction marked as ' . $status);
    }

    public function render()
    {
        return view('livewire.admin.dashboard.index'); // Ensure this matches your admin layout filename
    }
}

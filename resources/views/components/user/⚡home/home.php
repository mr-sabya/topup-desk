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
}; 
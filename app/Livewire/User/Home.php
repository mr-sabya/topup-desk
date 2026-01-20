<?php

namespace App\Livewire\User;

use App\Models\Category;
use App\Models\Provider;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Home extends Component
{
    public $step = 1;

    // Selections (Store IDs for better stability in LW3)
    public $selectedCategoryId = null;
    public $selectedProviderId = null;

    // Form inputs
    public $phone_number, $amount, $connection_type = 'Prepaid';

    /**
     * Computed properties are the standard way in LW3 
     * to fetch data used in the view.
     */
    #[Computed]
    public function categories()
    {
        return Category::where('is_active', true)->get();
    }

    #[Computed]
    public function providers()
    {
        return $this->selectedCategoryId
            ? Provider::where('category_id', $this->selectedCategoryId)->where('is_active', true)->get()
            : [];
    }

    #[Computed]
    public function selectedCategory()
    {
        return Category::find($this->selectedCategoryId);
    }

    #[Computed]
    public function selectedProvider()
    {
        return Provider::find($this->selectedProviderId);
    }

    public function selectCategory($id)
    {
        $this->selectedCategoryId = $id;
        $this->step = 2;
    }

    public function selectProvider($id)
    {
        $this->selectedProviderId = $id;
        $this->step = 3;
    }

    public function goBack()
    {
        if ($this->step > 1) {
            $this->step--;
            if ($this->step == 1) $this->selectedCategoryId = null;
            if ($this->step == 2) $this->selectedProviderId = null;
        }
    }

    public function submit()
    {
        $this->validate([
            'phone_number' => 'required|numeric|digits_between:10,15',
            'amount' => 'required|numeric|min:10',
        ]);

        Transaction::create([
            'category_id' => $this->selectedCategoryId,
            'provider_id' => $this->selectedProviderId,
            'phone_number' => $this->phone_number,
            'amount' => $this->amount,
            'connection_type' => $this->connection_type,
            'status' => 'pending',
            'trx_id' => 'TXN' . rand(100000, 999999),
        ]);

        session()->flash('success', 'Request sent! We are processing your payment.');
        $this->reset(['step', 'selectedCategoryId', 'selectedProviderId', 'phone_number', 'amount']);
    }
    

    public function render()
    {
        // Explicitly set the layout for Livewire 3
        return view('livewire.user.home');
    }
}

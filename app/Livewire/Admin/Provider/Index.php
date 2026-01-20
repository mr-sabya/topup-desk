<?php

namespace App\Livewire\Admin\Provider;

use App\Models\Provider;
use App\Models\Category;
use App\Models\Transaction; // Important: for safety check
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    public $category_id, $name, $logo, $is_active = true, $providerId;
    
    // For Delete Confirmation
    public $providerIdBeingDeleted = null;

    #[Url]
    public $search = '';

    #[Computed]
    public function providers()
    {
        return Provider::with('category')
            ->where('name', 'like', "%{$this->search}%")
            ->latest()
            ->get();
    }

    #[Computed]
    public function categories()
    {
        return Category::where('is_active', true)->get();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'category_id' => 'required|exists:categories,id',
            'logo' => $this->providerId ? 'nullable|image|max:1024' : 'required|image|max:1024',
        ]);

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
        ];

        // Only store if a new file was uploaded
        if ($this->logo && !is_string($this->logo)) {
            $data['logo'] = $this->logo->store('providers', 'public');
        }

        Provider::updateOrCreate(['id' => $this->providerId], $data);

        $this->dispatch('close-drawer');
        $this->resetForm();
        session()->flash('success', 'Provider saved successfully.');
    }

    public function edit($id)
    {
        $this->resetValidation();
        $p = Provider::findOrFail($id);
        $this->providerId = $id;
        $this->name = $p->name;
        $this->category_id = $p->category_id;
        $this->is_active = $p->is_active;

        $this->dispatch('open-drawer');
    }

    // Step 1: Open Confirmation Modal
    public function confirmDelete($id)
    {
        $this->providerIdBeingDeleted = $id;
        $this->dispatch('open-delete-modal');
    }

    // Step 2: Perform Delete with Safety Check
    public function delete()
    {
        $provider = Provider::findOrFail($this->providerIdBeingDeleted);

        // Safety Check: Check for Transactions
        $hasTransactions = Transaction::where('provider_id', $this->providerIdBeingDeleted)->exists();

        if ($hasTransactions) {
            session()->flash('error', "Cannot delete '{$provider->name}'. It has existing transactions in the history.");
            $this->dispatch('close-delete-modal');
            return;
        }

        if ($provider->logo) {
            Storage::disk('public')->delete($provider->logo);
        }

        $provider->delete();
        
        $this->dispatch('close-delete-modal');
        session()->flash('success', 'Provider deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'category_id', 'logo', 'providerId', 'providerIdBeingDeleted']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.provider.index');
    }
}
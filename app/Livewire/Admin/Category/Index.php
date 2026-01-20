<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    public $name, $slug, $icon, $is_active = true, $categoryId;
    public $search = '';

    // For Delete Confirmation
    public $categoryIdBeingDeleted = null;

    #[Computed]
    public function categories()
    {
        return Category::where('name', 'like', "%{$this->search}%")
            ->latest()
            ->get();
    }

    public function updatedName($v)
    {
        $this->slug = Str::slug($v);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $this->categoryId
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'is_active' => $this->is_active
        ];

        if ($this->icon && !is_string($this->icon)) {
            $data['icon'] = $this->icon->store('categories', 'public');
        }

        Category::updateOrCreate(['id' => $this->categoryId], $data);

        $this->dispatch('close-drawer');
        $this->resetForm();
        session()->flash('success', 'Category saved successfully.');
    }

    public function edit($id)
    {
        $cat = Category::find($id);
        $this->categoryId = $id;
        $this->name = $cat->name;
        $this->slug = $cat->slug;
        $this->is_active = $cat->is_active;

        $this->dispatch('open-drawer');
    }

    // Step 1: Open Modal
    public function confirmDelete($id)
    {
        $this->categoryIdBeingDeleted = $id;
        $this->dispatch('open-delete-modal');
    }

    // Step 2: Perform Delete
    public function delete()
    {
        $category = Category::findOrFail($this->categoryIdBeingDeleted);

        $hasProviders = Provider::where('category_id', $this->categoryIdBeingDeleted)->exists();

        if ($hasProviders) {
            session()->flash('error', "Cannot delete '{$category->name}'. It has active providers.");
            $this->dispatch('close-delete-modal');
            return;
        }

        if ($category->icon) {
            Storage::disk('public')->delete($category->icon);
        }

        $category->delete();

        $this->dispatch('close-delete-modal');
        session()->flash('success', 'Category deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'slug', 'icon', 'categoryId', 'categoryIdBeingDeleted']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.category.index');
    }
}

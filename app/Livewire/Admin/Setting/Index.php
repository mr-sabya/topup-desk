<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    // Setting fields
    public $site_name, $site_email, $site_phone, $site_logo;
    public $new_logo; // Temporary holder for upload

    public function mount()
    {
        // Load existing settings from DB
        $settings = Setting::pluck('value', 'key')->toArray();

        $this->site_name = $settings['site_name'] ?? 'My Topup Shop';
        $this->site_email = $settings['site_email'] ?? 'admin@example.com';
        $this->site_phone = $settings['site_phone'] ?? '01XXXXXXXXX';
        $this->site_logo = $settings['site_logo'] ?? null;
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|min:3',
            'site_email' => 'required|email',
            'new_logo' => 'nullable|image|max:1024',
        ]);

        $data = [
            'site_name' => $this->site_name,
            'site_email' => $this->site_email,
            'site_phone' => $this->site_phone,
        ];

        // Handle Logo Upload
        if ($this->new_logo) {
            // Delete old logo if it exists
            if ($this->site_logo) {
                Storage::disk('public')->delete($this->site_logo);
            }
            $data['site_logo'] = $this->new_logo->store('settings', 'public');
            $this->site_logo = $data['site_logo'];
        }

        // Save to DB
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        session()->flash('success', 'Settings updated successfully.');
        $this->reset(['new_logo']);
    }

    public function render()
    {
        return view('livewire.admin.setting.index');
    }
}

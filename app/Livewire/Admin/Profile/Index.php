<?php

namespace App\Livewire\Admin\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    // Profile Info
    public $name, $email, $avatar;
    public $new_avatar;

    // Password Update
    public $current_password, $password, $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatar = $user->profile_photo_path; // Assuming this column exists
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'new_avatar' => 'nullable|image|max:1024',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->new_avatar) {
            // Delete old avatar if it exists
            if ($this->avatar) {
                Storage::disk('public')->delete($this->avatar);
            }
            $data['profile_photo_path'] = $this->new_avatar->store('avatars', 'public');
            $this->avatar = $data['profile_photo_path'];
        }

        $user->update($data);

        session()->flash('success', 'Profile updated successfully!');
        $this->reset(['new_avatar']);
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password)
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('password_success', 'Password changed successfully!');
    }

    public function render()
    {
        return view('livewire.admin.profile.index');
    }
}
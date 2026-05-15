<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\RegistrationToken;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    public $search = '';
    public $now;

    // State untuk Modal Konfirmasi
    public $showConfirmModal = false;
    public $selectedUserId;
    public $selectedRole;
    public $selectedUserName;
    public function mount()
    {
        $this->now = now();
    }

    public function generateToken()
    {
        RegistrationToken::where('is_used', false)->delete();

        $newToken = strtoupper(Str::random(6));
        
        RegistrationToken::create([
            'token' => $newToken,
            'is_used' => false,
        ]);

        session()->flash('message', 'Token baru berhasil dibuat!');
    }

    public function confirmUpdateRole($userId, $newRole, $userName)
    {
        $this->selectedUserId = $userId;
        $this->selectedRole = $newRole;
        $this->selectedUserName = $userName;
        $this->showConfirmModal = true;
    }

    public function cancelUpdate()
    {
        $this->reset(['showConfirmModal', 'selectedUserId', 'selectedRole', 'selectedUserName']);
    }

    public function updateRole()
    {
        $user = User::findOrFail($this->selectedUserId);
        
        if ($user->id === Auth::id()) {
            session()->flash('error', 'Anda tidak bisa mengubah role Anda sendiri.');
            $this->cancelUpdate();
            return;
        }

        $user->update(['role' => $this->selectedRole]);
        session()->flash('message', "Role {$user->name} berhasil diperbarui.");
        
        $this->cancelUpdate();
    }

    public function render()
    {
        // Update waktu sekarang setiap kali render (untuk polling)
        $this->now = now();

        // Bersihkan yang expired
        RegistrationToken::where('is_used', false)
            ->where('created_at', '<', now()->subMinutes(5))
            ->delete();

        $activeToken = RegistrationToken::where('is_used', false)
                        ->where('created_at', '>=', now()->subMinutes(5))
                        ->latest()
                        ->first();

        return view('livewire.admin.user-management', [
            'users' => User::where(function($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                              ->orWhere('email', 'like', '%' . $this->search . '%');
                    })->get(),
            'token' => $activeToken,
        ])->layout('layouts.app');
    }
}
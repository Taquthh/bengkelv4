<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RegistrationToken; // Pastikan model Token di-import
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $isFirstUser = User::count() === 0;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Validasi Role: Harus salah satu dari 3 ini
            'role' => ['required', 'in:owner,kasir,keuangan'], 
            'token' => $isFirstUser ? ['nullable'] : ['required', 'exists:registration_tokens,token'],
        ]);

        if (!$isFirstUser) {
            $tokenRecord = RegistrationToken::where('token', $request->token)
                ->where('is_used', false)
                ->first();

            if (!$tokenRecord) {
                return back()->withErrors(['token' => 'Token tidak valid.']);
            }
            $tokenRecord->update(['is_used' => true]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Menggunakan role yang dipilih user dari form
            'role' => $request->role, 
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
<x-guest-layout>
    <div>
        <!-- Card Utama dengan Efek Glassmorphism Ringan -->
        <div class="max-w-2xl w-full space-y-8 bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-6 sm:p-10 border border-gray-100 dark:border-gray-700 transition-all duration-500">
            
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Bergabung dengan Kami</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Sistem Manajemen Bengkel <span class="text-blue-600 font-bold">FJS Auto Service</span></p>
            </div>

            <!-- Form Registrasi -->
            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="space-y-1">
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 ml-1" />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a3 3 0 100-6 3 3 0 000 6z"></path>
                                </svg>
                            </div>
                            <x-text-input id="name" class="block w-full pl-10 py-3 bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" type="text" name="name" :value="old('name')" required autofocus placeholder="Masukkan nama" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Role -->
                    <div class="space-y-1">
                        <x-input-label for="role" :value="__('Akses Peran')" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 ml-1" />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <select id="role" name="role" required class="block w-full pl-10 py-3 bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm dark:text-white">
                                @if(\App\Models\User::count() === 0)
                                    <option value="owner" selected>Owner (Setup Utama)</option>
                                @else
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Peran...</option>
                                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Staf Kasir</option>
                                    <option value="keuangan" {{ old('role') == 'keuangan' ? 'selected' : '' }}>Staf Keuangan</option>
                                @endif
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                    </div>
                </div>

                <!-- Email Address -->
                <div class="space-y-1">
                    <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 ml-1" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <x-text-input id="email" class="block w-full pl-10 py-3 bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" type="email" name="email" :value="old('email')" required placeholder="Masukkan Email" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div class="space-y-1">
                        <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 ml-1" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <x-text-input id="password" class="block w-full pl-10 pr-10 py-3 bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" type="password" name="password" required placeholder="••••••••" />
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-500 transition-colors" onclick="togglePassword('password', 'eye-icon')">
                                <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi')" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 ml-1" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <x-text-input id="password_confirmation" class="block w-full pl-10 pr-10 py-3 bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" type="password" name="password_confirmation" required placeholder="••••••••" />
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-500 transition-colors" onclick="togglePassword('password_confirmation', 'eye-confirm-icon')">
                                <svg id="eye-confirm-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Registration Token -->
                <div class="mt-6">
                    @if(\App\Models\User::exists())
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-2xl">
                            <x-input-label for="token" :value="__('Token Registrasi')" class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase mb-2" />
                            <x-text-input id="token" class="block w-full border-amber-200 dark:border-amber-800 focus:ring-amber-500 focus:border-amber-500" type="text" name="token" required placeholder="Ketik 6 digit token..." />
                            <p class="text-[10px] text-amber-600 dark:text-amber-500 mt-2 italic">*Mintalah token aktif kepada Owner bengkel.</p>
                        </div>
                    @else
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-2xl flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                                <strong>Setup Awal:</strong> Belum ada akun terdaftar. Anda akan otomatis menjadi <b>Owner</b> pengelola sistem tanpa memerlukan token.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5h14a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v2a2 2 0 01-2 2z"></path>
                        </svg>
                        Daftar dan Masuk Sistem
                    </button>
                </div>
            </form>

            <!-- Footer Links -->
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-500 underline decoration-2 underline-offset-4">Masuk ke sini</a></p>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-medium">© FJS AUTO SERVICE {{ date('Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Script Terpadu Toggle Password -->
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isPassword = input.type === 'password';
            
            input.type = isPassword ? 'text' : 'password';
            
            // Perbarui SVG Path
            icon.innerHTML = isPassword 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464m1.414 1.414l4.242 4.242m-4.242-4.242L12 12m-1.414-1.414L8.464 8.464m0 0L7.05 7.05m1.414 1.414L12 12"></path>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        }
    </script>
</x-guest-layout>
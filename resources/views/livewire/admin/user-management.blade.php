<div class="p-6 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto space-y-6"></div>
    <div class="max-w-7xl mx-auto space-y-8">
        
        <div class="fixed top-5 right-5 z-50 space-y-3 w-80">
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8"
                    class="flex items-center p-4 text-emerald-800 bg-emerald-50 rounded-xl shadow-lg border border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400">
                    <span class="text-sm font-medium">{{ session('message') }}</span>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">Manajemen Pengguna</h3>
                            <p class="text-sm text-gray-500">Kelola akses dan otoritas akun staf Anda.</p>
                        </div>
                        <div class="relative w-full md:w-64">
                            <input wire:model.live="search" type="text" placeholder="Cari akun..." 
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                            <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-gray-400 text-xs uppercase tracking-widest">
                                    <th class="px-4 py-2 font-semibold">Identitas</th>
                                    <th class="px-4 py-2 font-semibold">Otoritas</th>
                                    <th class="px-4 py-2 text-center font-semibold">Aksi Cepat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                <tr class="group bg-gray-50/50 dark:bg-gray-700/30 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20 rounded-2xl transition-all duration-200">
                                    <td class="px-4 py-4 rounded-l-2xl">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold">
                                                {{ substr($u->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $u->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex px-3 py-1 text-[10px] font-black tracking-tighter rounded-lg {{ $u->role === 'owner' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/50' }}">
                                            {{ strtoupper($u->role) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center rounded-r-2xl">
                                        <div class="flex items-center justify-center gap-2">
                                            <select wire:change="confirmUpdateRole({{ $u->id }}, $event.target.value, '{{ $u->name }}')" 
                                                class="text-xs font-semibold bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-indigo-500 transition-all cursor-pointer"
                                                {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                                                <option value="kasir" {{ $u->role == 'kasir' ? 'selected' : '' }}>KASIR</option>
                                                <option value="keuangan" {{ $u->role == 'keuangan' ? 'selected' : '' }}>KEUANGAN</option>
                                                <option value="owner" {{ $u->role == 'owner' ? 'selected' : '' }}>OWNER</option>
                                            </select>

                                            {{-- Tombol Hapus --}}
                                            @if($u->id !== auth()->id())
                                                <button wire:click="confirmDeleteUser({{ $u->id }}, '{{ $u->name }}')" 
                                                    class="h-9 px-4 bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase tracking-tight rounded-xl shadow-md shadow-red-200 dark:shadow-none transition-all active:scale-95 flex items-center justify-center border border-transparent">
                                                    Hapus Akun
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-200 dark:shadow-none overflow-hidden relative group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-500"></div>
                    
                    <h3 class="text-xl font-bold mb-2 relative z-10">Otorisasi Pendaftaran</h3>
                    <p class="text-indigo-100 text-xs mb-8 relative z-10 leading-relaxed">Gunakan fitur ini untuk mengizinkan staf baru mendaftar ke dalam sistem.</p>
                    
                    @if($token)
                        <div wire:poll.1s 
                            class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mb-6 text-center">
                            <span class="text-xs text-indigo-200 uppercase tracking-widest block mb-2">Token Aktif</span>
                            <code class="text-4xl font-black tracking-widest">{{ $token->token }}</code>
                            
                            <div class="mt-4 flex flex-col items-center gap-2">
                                <button onclick="navigator.clipboard.writeText('{{ $token->token }}')" 
                                    class="text-[10px] bg-white/20 hover:bg-white/30 px-4 py-1.5 rounded-full transition-all">
                                    Salin Kode
                                </button>
                                
                                {{-- Kalkulasi sisa waktu langsung via PHP --}}
                                @php
                                    $expiresAt = $token->created_at->addMinutes(5);
                                    $diff = $now->diffInSeconds($expiresAt, false);
                                    $minutes = floor($diff / 60);
                                    $seconds = $diff % 60;
                                @endphp

                                @if($diff > 0)
                                    <div class="mt-4 flex flex-col items-center gap-3">
                                        <!-- Label Status -->
                                        <div class="flex items-center gap-2 px-3 py-1 bg-emerald-500/20 rounded-full">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-200">Token Aktif</span>
                                        </div>

                                        <!-- Box Countdown -->
                                        <div class="flex items-center gap-1 font-mono">
                                            <div class="bg-gray-900/40 backdrop-blur-sm px-2 py-1.5 rounded-lg border border-white/10 min-w-[35px]">
                                                <span class="text-xl font-black text-white leading-none">{{ sprintf('%02d', $minutes) }}</span>
                                                <span class="block text-[8px] text-indigo-300 uppercase font-sans">Min</span>
                                            </div>
                                            
                                            <span class="text-xl font-bold text-white/50 mb-4">:</span>

                                            <div class="bg-gray-900/40 backdrop-blur-sm px-2 py-1.5 rounded-lg border border-white/10 min-w-[35px]">
                                                <span class="text-xl font-black text-white leading-none">{{ sprintf('%02d', $seconds) }}</span>
                                                <span class="block text-[8px] text-indigo-300 uppercase font-sans">Det</span>
                                            </div>
                                        </div>

                                        <p class="text-[9px] text-indigo-200/60 italic">Otomatis hangus saat waktu habis</p>
                                    </div>
                                @else
                                    <div class="mt-4 px-4 py-2 bg-red-500/20 border border-red-500/50 rounded-xl">
                                        <span class="text-xs text-red-200 font-medium">⚠️ Token telah kadaluarsa</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <button wire:click="generateToken" 
                            class="w-full py-4 bg-white text-indigo-600 font-black rounded-2xl shadow-xl hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-200">
                            GENERATE TOKEN
                        </button>
                    @endif

                    <ul class="mt-8 space-y-3 text-[10px] text-indigo-100/80">
                        <li class="flex items-start gap-2 italic">
                            <span>⚡</span>
                            <span>Satu token hanya untuk satu kali pendaftaran sukses.</span>
                        </li>
                        <li class="flex items-start gap-2 italic">
                            <span>⏱️</span>
                            <span>Masa berlaku token otomatis hangus dalam 5 menit.</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Konfirmasi -->
@if($showConfirmModal)
    <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Overlay Backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700">
                <div class="p-8">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto bg-amber-100 dark:bg-amber-900/30 rounded-full mb-6">
                        <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">Konfirmasi Perubahan</h3>
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            Apakah Anda yakin ingin mengubah role <span class="font-bold text-indigo-600">{{ $selectedUserName }}</span> menjadi <span class="uppercase font-bold px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">{{ $selectedRole }}</span>?
                        </p>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button wire:click="cancelUpdate" type="button" 
                            class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                            Batalkan
                        </button>
                        <button wire:click="updateRole" type="button" 
                            class="flex-1 px-4 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 dark:shadow-none transition-all">
                            Ya, Perbarui
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Modal Konfirmasi Hapus User -->
@if($showDeleteModal)
    <div class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700">
                <div class="p-8">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full mb-6 text-red-600 dark:text-red-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hapus Pengguna?</h3>
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            Tindakan ini akan menghapus akun <span class="font-bold text-red-600">{{ $deleteUserName }}</span> secara permanen. Data yang terkait mungkin akan hilang.
                        </p>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button wire:click="cancelDelete" type="button" 
                            class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                            Batal
                        </button>
                        <button wire:click="deleteUser" type="button" 
                            class="flex-1 px-4 py-3 bg-red-600 text-white text-sm font-bold rounded-2xl hover:bg-red-700 shadow-lg shadow-red-200 dark:shadow-none transition-all">
                            Ya, Hapus Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
    
    <style>
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.9; transform: scale(0.98); }
    }
    .animate-pulse-slow {
        animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
</div>


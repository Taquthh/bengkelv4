<div>
    @if($showAddItemModal && $selectedTransaction)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        {{-- Fixed modal height and improved scrolling structure --}}
        <div class="bg-white rounded-3xl shadow-2xl max-w-6xl w-full h-[90vh] flex flex-col overflow-hidden">
            <!-- Enhanced header with better gradient and typography -->
            <div class="bg-gradient-to-br from-slate-800 via-slate-700 to-slate-600 text-white p-8 flex-shrink-0">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold tracking-tight">Tambah Item Transaksi</h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <p class="text-slate-200 font-medium">{{ $selectedTransaction->invoice }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-200 p-3 rounded-xl group">
                        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Made this section scrollable with proper height management --}}
            <div class="flex-1 overflow-y-auto">
                 {{-- Made tabs smaller and more precise while maintaining professional look --}}
                <div class="bg-gradient-to-r from-slate-50 to-gray-50 border-b border-slate-200 sticky top-0 z-10">
                    <div class="flex">
                        <input type="radio" id="tab-barang" name="item-tabs" class="hidden" 
                               {{ $activeTab === 'barang' ? 'checked' : '' }} 
                               wire:click="$set('activeTab', 'barang')">
                        <label for="tab-barang" class="flex-1 px-4 py-4 text-center cursor-pointer transition-all duration-300 border-b-3 {{ $activeTab === 'barang' ? 'border-blue-500 bg-white text-blue-600 font-semibold shadow-sm' : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-white/50' }}">
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-7 h-7 rounded-lg {{ $activeTab === 'barang' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center transition-colors duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium">Barang Stok</span>
                                    @if(count($selectedBarangItems) > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-0.5">{{ count($selectedBarangItems) }}</span>
                                    @endif
                                </div>
                            </div>
                        </label>

                        <input type="radio" id="tab-manual" name="item-tabs" class="hidden" 
                               {{ $activeTab === 'manual' ? 'checked' : '' }}
                               wire:click="$set('activeTab', 'manual')">
                        <label for="tab-manual" class="flex-1 px-4 py-4 text-center cursor-pointer transition-all duration-300 border-b-3 {{ $activeTab === 'manual' ? 'border-amber-500 bg-white text-amber-600 font-semibold shadow-sm' : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-white/50' }}">
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-7 h-7 rounded-lg {{ $activeTab === 'manual' ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center transition-colors duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium">Manual/Indent</span>
                                    @if(count($manualItems) > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mt-0.5">{{ count($manualItems) }}</span>
                                    @endif
                                </div>
                            </div>
                        </label>

                        <input type="radio" id="tab-jasa" name="item-tabs" class="hidden" 
                               {{ $activeTab === 'jasa' ? 'checked' : '' }}
                               wire:click="$set('activeTab', 'jasa')">
                        <label for="tab-jasa" class="flex-1 px-4 py-4 text-center cursor-pointer transition-all duration-300 border-b-3 {{ $activeTab === 'jasa' ? 'border-violet-500 bg-white text-violet-600 font-semibold shadow-sm' : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-white/50' }}">
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-7 h-7 rounded-lg {{ $activeTab === 'jasa' ? 'bg-violet-100 text-violet-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center transition-colors duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium">Jasa/Service</span>
                                    @if(count($jasaItems) > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 mt-0.5">{{ count($jasaItems) }}</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="p-8">
                    @if($errors->any())
                        <!-- Enhanced error display with better styling -->
                        <div class="mb-8 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-400 rounded-r-2xl p-6 shadow-sm">
                            <div class="flex">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-red-800 font-semibold text-lg">Terdapat kesalahan:</h4>
                                    <ul class="mt-3 text-red-700 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li class="flex items-center space-x-2">
                                                <div class="w-1.5 h-1.5 bg-red-400 rounded-full"></div>
                                                <span>{{ $error }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($activeTab === 'barang')
                        <!-- Barang dari Stok Tab -->
                        <div class="space-y-8">
                            <!-- Enhanced info banner with better design -->
                            <div class="bg-gradient-to-r from-blue-50 via-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-blue-900 text-lg">Pilih Barang dari Stok</h4>
                                        <p class="text-blue-700 mt-1 leading-relaxed">Pilih barang yang tersedia di inventory untuk ditambahkan ke transaksi</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced form with better styling and layout -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <h5 class="font-semibold text-slate-900 text-lg">Tambah Barang</h5>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Pilih Barang</label>
                                        <select wire:model.live="selectedBarangId" class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-slate-700">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($availableBarangs as $barang)
                                                <option value="{{ $barang['id'] }}">
                                                    {{ $barang['nama'] }} 
                                                    @if($barang['merk']) - {{ $barang['merk'] }} @endif
                                                    (Stok: {{ $barang['stok'] }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Jumlah</label>
                                        <input wire:model="itemJumlah" type="number" min="1" class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-slate-700" placeholder="1">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Harga Jual</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                                            <input wire:model="itemHargaJual" type="number" min="0" step="1000" class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-slate-700" placeholder="0">
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-end">
                                        <button wire:click="addBarangToList" class="w-full px-6 py-3.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl font-medium">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Tambah</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Added scrollable container for selected items list --}}
                            @if(count($selectedBarangItems) > 0)
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                                    <div class="flex items-center space-x-3 mb-6">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="font-semibold text-slate-900 text-lg">Barang yang akan ditambahkan</h5>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ count($selectedBarangItems) }} item</span>
                                    </div>
                                    
                                    {{-- Added max height and scrolling for items list --}}
                                    <div class="max-h-64 overflow-y-auto space-y-4 pr-2">
                                        @foreach($selectedBarangItems as $index => $item)
                                            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-slate-900 text-lg">{{ $item['nama'] }}</span>
                                                            @if($item['merk']) 
                                                                <span class="text-slate-600 font-medium"> - {{ $item['merk'] }}</span> 
                                                            @endif
                                                            <div class="text-slate-600 mt-2 flex items-center space-x-4">
                                                                <span class="bg-white px-3 py-1 rounded-lg text-sm font-medium">{{ $item['jumlah'] }} × Rp{{ number_format($item['harga_jual'], 0, ',', '.') }}</span>
                                                                <span class="text-blue-600 font-bold text-lg">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button wire:click="removeBarangFromList({{ $index }})" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-3 rounded-xl transition-all duration-200 group">
                                                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="border-t border-slate-200 mt-6 pt-6">
                                        <div class="text-right">
                                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                                                Total Barang: Rp{{ number_format(collect($selectedBarangItems)->sum('subtotal'), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @elseif($activeTab === 'manual')
                        <!-- Manual Items Tab -->
                        <div class="space-y-8">
                            <!-- Enhanced info banner for manual items -->
                            <div class="bg-gradient-to-r from-amber-50 via-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-amber-900 text-lg">Barang Manual/Indent</h4>
                                        <p class="text-amber-700 mt-1 leading-relaxed">Barang yang perlu diorder atau tidak ada di stok inventory</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Manual Item Form -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <h5 class="font-semibold text-slate-900 text-lg">Tambah Barang Manual</h5>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Nama Barang</label>
                                        <input wire:model="nama_barang_manual" type="text" class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-500 transition-all duration-200 text-slate-700" placeholder="Nama barang">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Jumlah</label>
                                        <input wire:model="jumlah_manual" type="number" min="1" class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-500 transition-all duration-200 text-slate-700" placeholder="1">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Satuan</label>
                                        <select wire:model="satuan_manual" class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-500 transition-all duration-200 text-slate-700">
                                            <option value="pcs">pcs</option>
                                            <option value="set">set</set</option>
                                            <option value="unit">unit</option>
                                            <option value="meter">meter</option>
                                            <option value="liter">liter</liter>
                                            <option value="kg">kg</option>
                                            <option value="dus">dus</option>
                                            <option value="box">box</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Harga Jual</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                                            <input wire:model="harga_jual_manual" type="number" min="0" step="1000" class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-500 transition-all duration-200 text-slate-700" placeholder="0">
                                        </div>
                                        <small class="text-slate-500 text-xs mt-2 block">Harga jual ke pelanggan</small>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Harga Beli</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                                            <input wire:model="harga_beli_manual" type="number" min="0" step="1000" class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-500 transition-all duration-200 text-slate-700" placeholder="0">
                                        </div>
                                        <small class="text-slate-500 text-xs mt-2 block">Harga beli/modal</small>
                                    </div>
                                </div>
                                
                                <div class="mt-8 flex justify-end">
                                    <button wire:click="addManualToList" class="px-8 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span>Tambah Manual</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Added scrollable container for manual items list --}}
                            @if(count($manualItems) > 0)
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                                    <div class="flex items-center space-x-3 mb-6">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="font-semibold text-slate-900 text-lg">Barang manual yang akan ditambahkan</h5>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">{{ count($manualItems) }} item</span>
                                    </div>
                                    
                                    {{-- Added max height and scrolling for manual items list --}}
                                    <div class="max-h-64 overflow-y-auto space-y-4 pr-2">
                                        @foreach($manualItems as $index => $item)
                                            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl hover:from-amber-100 hover:to-orange-100 transition-all duration-200">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-slate-900 text-lg">{{ $item['nama_barang'] }}</span>
                                                            <div class="text-slate-600 mt-2 flex items-center space-x-4">
                                                                <span class="bg-white px-3 py-1 rounded-lg text-sm font-medium">{{ $item['jumlah'] }} {{ $item['satuan'] }} × Rp{{ number_format($item['harga_jual'], 0, ',', '.') }}</span>
                                                                <span class="text-amber-600 font-bold text-lg">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="text-xs text-slate-500 mt-1 bg-slate-100 px-2 py-1 rounded-lg inline-block">Modal: Rp{{ number_format($item['harga_beli_manual'], 0, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button wire:click="removeManualFromList({{ $index }})" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-3 rounded-xl transition-all duration-200 group">
                                                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="border-t border-slate-200 mt-6 pt-6">
                                        <div class="text-right">
                                            <span class="text-2xl font-bold bg-gradient-to-r from-amber-600 to-amber-800 bg-clip-text text-transparent">
                                                Total Manual: Rp{{ number_format(collect($manualItems)->sum('subtotal'), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @else
                        <div class="space-y-8">
                            <!-- Enhanced info banner for services -->
                            <div class="bg-gradient-to-r from-violet-50 via-violet-50 to-purple-50 border border-violet-200 rounded-2xl p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-violet-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-violet-900 text-lg">Jasa/Service</h4>
                                        <p class="text-violet-700 mt-1 leading-relaxed">Tambahkan layanan atau service yang dikerjakan untuk pelanggan</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Jasa Form -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <h5 class="font-semibold text-slate-900 text-lg">Tambah Jasa/Service</h5>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Nama Jasa/Service</label>
                                        <input wire:model="namaJasaBaru" type="text" class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-violet-100 focus:border-violet-500 transition-all duration-200 text-slate-700" placeholder="Contoh: Ganti Oli, Service AC, dll">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Harga Jasa</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                                            <input wire:model="hargaJasaBaru" type="number" min="0" step="1000" class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-violet-100 focus:border-violet-500 transition-all duration-200 text-slate-700" placeholder="0">
                                        </div>
                                        
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-3">Keterangan</label>
                                        <input wire:model="keteranganJasaBaru" type="text" class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-violet-100 focus:border-violet-500 transition-all duration-200 text-slate-700" placeholder="Keterangan tambahan (opsional)">

                                    </div>
                                    
                                    <div class="flex items-end">
                                        <button wire:click="addJasaToList" class="w-full px-6 py-3.5 bg-gradient-to-r from-violet-500 to-violet-600 hover:from-violet-600 hover:to-violet-700 text-white rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl font-medium">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Tambah</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Jasa Items -->
                            @if(count($jasaItems) > 0)
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                                    <div class="flex items-center space-x-3 mb-6">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="font-semibold text-slate-900 text-lg">Jasa yang akan ditambahkan</h5>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-100 text-violet-800">{{ count($jasaItems) }} item</span>
                                    </div>
                                    
                                    {{-- Added max height and scrolling for jasa items list --}}
                                    <div class="max-h-64 overflow-y-auto space-y-4 pr-2">
                                        @foreach($jasaItems as $index => $item)
                                            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-violet-50 to-purple-50 border border-violet-200 rounded-2xl hover:from-violet-100 hover:to-purple-100 transition-all duration-200">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-slate-900 text-lg">{{ $item['nama_jasa'] }}</span>
                                                            @if($item['keterangan'])
                                                                <div class="text-slate-600 mt-1 bg-slate-100 px-3 py-1 rounded-lg text-sm inline-block">{{ $item['keterangan'] }}</div>
                                                            @endif
                                                            <div class="text-violet-600 font-bold text-lg mt-2">
                                                                Rp{{ number_format($item['harga_jasa'], 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button wire:click="removeJasaFromList({{ $index }})" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-3 rounded-xl transition-all duration-200 group">
                                                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="border-t border-slate-200 mt-6 pt-6">
                                        <div class="text-right">
                                            <span class="text-2xl font-bold bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                                                Total Jasa: Rp{{ number_format(collect($jasaItems)->sum('harga_jasa'), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Enhanced summary section with better design -->
                    @php
                        $grandTotal = collect($selectedBarangItems)->sum('subtotal') + 
                                     collect($manualItems)->sum('subtotal') + 
                                     collect($jasaItems)->sum('harga_jasa');
                        $totalItems = count($selectedBarangItems) + count($manualItems) + count($jasaItems);
                    @endphp
                    
                    @if($grandTotal > 0)
                        <div class="bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 border-2 border-emerald-200 rounded-3xl p-8 mt-8 shadow-lg">
                            <div class="flex justify-between items-start">
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2-2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-emerald-900">Ringkasan Transaksi</h3>
                                    </div>
                                    <div class="space-y-3">
                                        @if(count($selectedBarangItems) > 0)
                                            <div class="flex justify-between items-center bg-white/70 px-4 py-3 rounded-xl">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                                                        <span class="text-blue-600 text-xs font-bold">{{ count($selectedBarangItems) }}</span>
                                                    </div>
                                                    <span class="text-emerald-800 font-medium">Barang Stok</span>
                                                </div>
                                                <span class="font-bold text-emerald-900">Rp{{ number_format(collect($selectedBarangItems)->sum('subtotal'), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @if(count($manualItems) > 0)
                                            <div class="flex justify-between items-center bg-white/70 px-4 py-3 rounded-xl">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center">
                                                        <span class="text-amber-600 text-xs font-bold">{{ count($manualItems) }}</span>
                                                    </div>
                                                    <span class="text-emerald-800 font-medium">Barang Manual</span>
                                                </div>
                                                <span class="font-bold text-emerald-900">Rp{{ number_format(collect($manualItems)->sum('subtotal'), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @if(count($jasaItems) > 0)
                                            <div class="flex justify-between items-center bg-white/70 px-4 py-3 rounded-xl">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-6 h-6 bg-violet-100 rounded-lg flex items-center justify-center">
                                                        <span class="text-violet-600 text-xs font-bold">{{ count($jasaItems) }}</span>
                                                    </div>
                                                    <span class="text-emerald-800 font-medium">Jasa/Service</span>
                                                </div>
                                                <span class="font-bold text-emerald-900">Rp{{ number_format(collect($jasaItems)->sum('harga_jasa'), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right space-y-2">
                                    <div class="text-3xl font-black bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                        Rp{{ number_format($grandTotal, 0, ',', '.') }}
                                    </div>
                                    <div class="text-emerald-700 font-medium bg-emerald-100 px-3 py-1 rounded-full text-sm">
                                        {{ $totalItems }} item total
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Made footer sticky at bottom with flex-shrink-0 --}}
            <div class="bg-gradient-to-r from-slate-50 to-gray-50 px-8 py-6 border-t border-slate-200 flex justify-between items-center flex-shrink-0">
                <button wire:click="closeModal" class="px-8 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 hover:border-slate-400 font-semibold transition-all duration-200 flex items-center space-x-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Batal</span>
                </button>
                
                @if($grandTotal > 0)
                    <button wire:click="saveAllItemsToTransaction" class="px-10 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all duration-200 flex items-center space-x-3 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan {{ $totalItems }} Item</span>
                        <div class="bg-white/20 px-2 py-1 rounded-lg text-sm">
                            Rp{{ number_format($grandTotal, 0, ',', '.') }}
                        </div>
                    </button>
                @else
                    <div class="text-slate-500 bg-slate-100 px-6 py-3 rounded-xl font-medium">
                        Pilih minimal 1 item untuk melanjutkan
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
</div>

{{-- resources/views/livewire/keuangan/dashboard.blade.php --}}
<div>
    {{-- Header Section --}}
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Keuangan</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Bengkel Firdaus Jaya Sentosa - {{ now()->format('d F Y') }}
                </p>
            </div>
            
            {{-- Period Filters --}}
            <div class="flex flex-col sm:flex-row gap-4 mt-4 lg:mt-0">
                <select wire:model="selectedPeriod" 
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="this_week">Minggu Ini</option>
                    <option value="last_week">Minggu Lalu</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="last_month">Bulan Lalu</option>
                    <option value="this_quarter">Kuartal Ini</option>
                    <option value="this_year">Tahun Ini</option>
                    <option value="custom">Custom Range</option>
                </select>
                
                @if($selectedPeriod === 'custom')
                <div class="flex gap-2">
                    <input type="date" wire:model="customDateFrom" 
                           class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                    <input type="date" wire:model="customDateTo" 
                           class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                </div>
                @endif
                
                <button wire:click="loadData" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Pendapatan --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <i class="fas fa-arrow-up text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                    <i class="fas fa-arrow-down text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Laba Bersih --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Laba Bersih</p>
                    <p class="text-2xl font-bold {{ $labaBersih >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                    </p>
                    @if($growthRate != 0)
                    <p class="text-xs {{ $growthRate >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        {{ $growthRate >= 0 ? '+' : '' }}{{ number_format($growthRate, 1) }}% vs periode sebelumnya
                    </p>
                    @endif
                </div>
                <div class="bg-{{ $labaBersih >= 0 ? 'green' : 'red' }}-100 dark:bg-{{ $labaBersih >= 0 ? 'green' : 'red' }}-900 p-3 rounded-full">
                    <i class="fas fa-chart-line text-{{ $labaBersih >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $labaBersih >= 0 ? 'green' : 'red' }}-400 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Piutang --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Piutang</p>
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                        Rp {{ number_format($totalPiutang, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $jumlahTransaksi }} transaksi periode ini
                    </p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                    <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Chart Pendapatan vs Pengeluaran --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Pendapatan vs Pengeluaran
            </h3>
            <div class="h-80" id="revenueChart">
                <canvas id="chartCanvas"></canvas>
            </div>
        </div>

        {{-- Top 10 Transaksi --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Top 10 Transaksi Service
                </h3>
                <a href="" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse($topTransaksi as $transaksi)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $transaksi['invoice'] }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $transaksi['pelanggan'] }} - {{ $transaksi['nopol'] }}
                        </p>
                        <p class="text-xs text-gray-500">{{ Carbon\Carbon::parse($transaksi['tanggal'])->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900 dark:text-white">
                            Rp {{ number_format($transaksi['total'], 0, ',', '.') }}
                        </p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $transaksi['status_pembayaran'] === 'lunas' 
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                : 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' }}">
                            {{ ucfirst($transaksi['status_pembayaran']) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Tidak ada transaksi pada periode ini</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Piutang Outstanding --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Piutang Outstanding
            </h3>
            <a href="" 
               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Kelola Piutang →
            </a>
        </div>
        
        @if(count($piutangData) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Invoice
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Jatuh Tempo
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($piutangData as $piutang)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $piutang['invoice'] }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $piutang['nopol'] }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $piutang['pelanggan'] }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($piutang['jumlah'], 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $piutang['jatuh_tempo'] ? Carbon\Carbon::parse($piutang['jatuh_tempo'])->format('d/m/Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($piutang['status'] === 'overdue')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Overdue {{ abs($piutang['overdue_days']) }} hari
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $piutang['overdue_days'] }} hari lagi
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-check-circle text-green-400 text-4xl mb-4"></i>
            <p class="text-gray-500">Tidak ada piutang outstanding</p>
        </div>
        @endif
    </div>

    {{-- Export Actions --}}
    <div class="mt-8 flex justify-end space-x-4">
        <button wire:click="export('excel')" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-file-excel mr-2"></i>Export Excel
        </button>
        <button wire:click="export('pdf')" 
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-file-pdf mr-2"></i>Export PDF
        </button>
    </div>
</div>

{{-- Chart.js Script --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartCanvas');
    if (!ctx) return;
    
    let chart;

    function updateChart() {
        const chartData = @json($chartData);
        
        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.date_formatted),
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: chartData.map(item => item.pendapatan),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Pengeluaran',
                        data: chartData.map(item => item.pengeluaran),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Laba',
                        data: chartData.map(item => item.laba),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: false,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + 
                                       new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value);
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    updateChart();

    // Update chart when data changes
    window.addEventListener('livewire:load', updateChart);
    window.addEventListener('livewire:update', updateChart);

    // Auto refresh every 5 minutes
    setInterval(function() {
        @this.call('loadData');
    }, 300000);
});

// Notification handler
window.addEventListener('showNotification', event => {
    // You can integrate with your preferred notification library
    // For example: toastr, sweetalert2, or custom notification
    alert(event.detail[0]); // Simple alert for demo
});
</script>
@endpush

@push('styles')
<style>
.chart-container {
    position: relative;
    height: 320px;
    width: 100%;
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Dark mode scrollbar */
.dark .overflow-y-auto::-webkit-scrollbar-track {
    background: #374151;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

/* Loading skeleton */
.skeleton {
    animation: skeleton-loading 1s linear infinite alternate;
}

@keyframes skeleton-loading {
    0% { background-color: #e2e8f0; }
    100% { background-color: #cbd5e1; }
}

.dark .skeleton {
    background-color: #374151;
}

@keyframes dark-skeleton-loading {
    0% { background-color: #374151; }
    100% { background-color: #4b5563; }
}

.dark .skeleton {
    animation: dark-skeleton-loading 1s linear infinite alternate;
}
</style>
@endpush
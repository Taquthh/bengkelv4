<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PengeluaranOperasional;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use DateTimeInterface;

#[Layout('layouts.app')]

class PengeluaranOperasionals extends Component
{
    public $tanggal;
    public $nama_item;
    public $jumlah_pengeluaran;
    public $pengeluaranId;

    public $showModal = false; // untuk modal edit
    public $showAddModal = false; // untuk modal tambah data
    public $currentWeekOffset = 0; // 0 = minggu ini, 1 = minggu lalu, dst

    public $hasDataInPreviousWeeks = false;
    public $availableWeeks = [];

    protected $rules = [
        'tanggal'            => 'required|date',
        'nama_item'          => 'required|string|max:255',
        'jumlah_pengeluaran' => 'required|numeric|min:1',
    ];

    protected $messages = [
        'tanggal.required' => 'Tanggal harus diisi.',
        'tanggal.date' => 'Format tanggal tidak valid.',
        'nama_item.required' => 'Nama item harus diisi.',
        'nama_item.string' => 'Nama item harus berupa teks.',
        'nama_item.max' => 'Nama item maksimal 255 karakter.',
        'jumlah_pengeluaran.required' => 'Jumlah pengeluaran harus diisi.',
        'jumlah_pengeluaran.numeric' => 'Jumlah pengeluaran harus berupa angka.',
        'jumlah_pengeluaran.min' => 'Jumlah pengeluaran minimal Rp 1.',
    ];

    public function mount()
    {
        // Set tanggal hari ini sebagai default
        $this->tanggal = Carbon::now()->format('Y-m-d');
        $this->initializeAvailableWeeks();
    }

    // Method untuk navigasi minggu
    public function goToPreviousWeek()
    {
        // Check if there's data in the previous week before allowing navigation
        $nextOffset = $this->currentWeekOffset + 1;
        if ($this->hasDataInWeek($nextOffset)) {
            $this->currentWeekOffset = $nextOffset;
        }
    }

    public function goToNextWeek()
    {
        if ($this->currentWeekOffset > 0) {
            $this->currentWeekOffset--;
        }
    }

    public function goToCurrentWeek()
    {
        $this->currentWeekOffset = 0;
    }

    private function hasDataInWeek($offset)
    {
        $week = $this->getWeekByOffset($offset);
        
        return PengeluaranOperasional::whereBetween('tanggal', [
            $week['monday']->format('Y-m-d'),
            $week['saturday']->format('Y-m-d')
        ])
        ->whereRaw('DAYOFWEEK(tanggal) != 1') // Exclude Sundays
        ->exists();
    }

    private function initializeAvailableWeeks()
    {
        $this->availableWeeks = [];
        $offset = 0;
        
        // Find all weeks with data, starting from current week
        while ($offset < 52) { // Limit to 1 year for performance
            if ($this->hasDataInWeek($offset)) {
                $week = $this->getWeekByOffset($offset);
                $this->availableWeeks[] = [
                    'offset' => $offset,
                    'monday' => $week['monday'],
                    'saturday' => $week['saturday'],
                    'is_current' => $offset === 0
                ];
            } else if ($offset > 0) {
                // Stop searching if no data found in non-current week
                break;
            }
            $offset++;
        }
        
        $this->hasDataInPreviousWeeks = count($this->availableWeeks) > 1;
    }

    private function getWeekByOffset($offset = 0)
    {
        $monday = Carbon::now()->startOfWeek(Carbon::MONDAY)->subWeeks($offset);
        $saturday = $monday->copy()->addDays(5);
        
        return [
            'monday' => $monday,
            'saturday' => $saturday,
            'is_current' => $offset === 0
        ];
    }

    public function simpan()
    {
        $this->validate();

        // Validasi hari Minggu
        $this->validateNotSunday($this->tanggal);

        PengeluaranOperasional::create([
            'tanggal'            => $this->tanggal,
            'nama_item'          => $this->nama_item,
            'jumlah_pengeluaran' => $this->jumlah_pengeluaran,
        ]);

        session()->flash('success', 'Pengeluaran berhasil ditambahkan!');
        $this->resetInput();
        $this->showAddModal = false;
        
        $this->initializeAvailableWeeks();
    }

    public function edit($id)
    {
        $pengeluaran = PengeluaranOperasional::findOrFail($id);

        $this->pengeluaranId      = $pengeluaran->id;
        $this->tanggal            = $pengeluaran->tanggal->format('Y-m-d');
        $this->nama_item          = $pengeluaran->nama_item;
        $this->jumlah_pengeluaran = $pengeluaran->jumlah_pengeluaran;

        $this->showModal = true; // buka modal
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->reset(['tanggal', 'nama_item', 'jumlah_pengeluaran']);
    }

    public function update()
    {
        $this->validate();

        // Validasi hari Minggu
        $this->validateNotSunday($this->tanggal);

        $pengeluaran = PengeluaranOperasional::findOrFail($this->pengeluaranId);
        $pengeluaran->update([
            'tanggal'            => $this->tanggal,
            'nama_item'          => $this->nama_item,
            'jumlah_pengeluaran' => $this->jumlah_pengeluaran,
        ]);

        session()->flash('success', 'Pengeluaran berhasil diperbarui!');

        $this->resetInput();
        $this->showModal = false;
    }

    public function delete($id)
    {
        PengeluaranOperasional::findOrFail($id)->delete();
        session()->flash('success', 'Pengeluaran berhasil dihapus!');
        
        $this->initializeAvailableWeeks();
    }

    private function validateNotSunday($date)
    {
        $carbonDate = Carbon::parse($date);
        
        if ($carbonDate->dayOfWeek === Carbon::SUNDAY) {
            throw ValidationException::withMessages([
                'tanggal' => 'Tidak dapat menambahkan pengeluaran pada hari Minggu.'
            ]);
        }
    }

    private function resetInput()
    {
        $this->reset(['tanggal', 'nama_item', 'jumlah_pengeluaran', 'pengeluaranId']);
        $this->tanggal = Carbon::now()->format('Y-m-d'); // Reset ke tanggal hari ini
    }

    /**
     * Mengelompokkan data pengeluaran berdasarkan minggu (Senin-Jumat)
     */
    private function groupByWeek($data)
    {
        $weeks = [];
        
        foreach ($data as $pengeluaran) {
            $date = $pengeluaran->tanggal;
            
            // Skip jika hari Minggu
            if ($date->dayOfWeek === Carbon::SUNDAY) {
                continue;
            }
            
            // Cari hari Senin dari minggu ini
            $monday = $date->copy()->startOfWeek(Carbon::MONDAY);
            $saturday = $monday->copy()->addDays(5); // Sabtu
            
            $weekKey = $monday->format('Y-m-d');
            
            if (!isset($weeks[$weekKey])) {
                $weeks[$weekKey] = [
                    'monday' => $monday,
                    'saturday' => $saturday,
                    'data' => collect([]),
                    'total' => 0,
                    'is_current_week' => $this->isCurrentWeek($monday)
                ];
            }
            
            $weeks[$weekKey]['data']->push($pengeluaran);
            $weeks[$weekKey]['total'] += $pengeluaran->jumlah_pengeluaran;
        }
        
        // Urutkan berdasarkan tanggal (terbaru dulu)
        krsort($weeks);
        
        return $weeks;
    }

    /**
     * Mengecek apakah minggu tersebut adalah minggu ini
     */
    private function isCurrentWeek($monday)
    {
        if (!$monday instanceof Carbon) {
            $monday = Carbon::parse($monday);
        }

        $currentMonday = Carbon::now()->startOfWeek(Carbon::MONDAY);

        return $monday->isSameDay($currentMonday);
    }

    /**
     * Mendapatkan total pengeluaran per minggu
     */
    public function getTotalThisWeek()
    {
        $currentMonday = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $currentSaturday = $currentMonday->copy()->addDays(5);
        
        return PengeluaranOperasional::whereBetween('tanggal', [
            $currentMonday->format('Y-m-d'),
            $currentSaturday->format('Y-m-d')
        ])->sum('jumlah_pengeluaran');
    }

    /**
     * Mendapatkan total keseluruhan pengeluaran
     */
    public function getTotalOverall()
    {
        return PengeluaranOperasional::whereRaw('DAYOFWEEK(tanggal) != 1')
            ->sum('jumlah_pengeluaran');
    }

    public function render()
    {
        $this->initializeAvailableWeeks();
        
        $currentWeek = $this->getWeekByOffset($this->currentWeekOffset);
        
        // Ambil data pengeluaran untuk minggu yang sedang dilihat
        $weekData = PengeluaranOperasional::whereBetween('tanggal', [
            $currentWeek['monday']->format('Y-m-d'),
            $currentWeek['saturday']->format('Y-m-d')
        ])
        ->orderBy('tanggal', 'desc')
        ->get()
        ->filter(function ($pengeluaran) {
            return $pengeluaran->tanggal->dayOfWeek !== Carbon::SUNDAY;
        });

        // Total minggu yang sedang dilihat
        $totalWeekInView = $weekData->sum('jumlah_pengeluaran');
        
        // Total minggu ini (selalu minggu ini, tidak terpengaruh offset)
        $totalMingguIni = $this->getTotalThisWeek();
        
        // Total keseluruhan
        $totalPengeluaran = PengeluaranOperasional::whereRaw('DAYOFWEEK(tanggal) != 1')
            ->sum('jumlah_pengeluaran');

        $pengeluaranPerMinggu = $this->groupByWeek(
            PengeluaranOperasional::orderBy('tanggal', 'desc')->get()
        );

        return view('livewire.pengeluaran-operasionals', [
            'daftarPengeluaran'   => $weekData,
            'currentWeek'         => $currentWeek,
            'totalWeekInView'     => $totalWeekInView,
            'totalPengeluaran'    => $totalPengeluaran,
            'totalMingguIni'      => $totalMingguIni,
            'isCurrentWeek'       => $this->isCurrentWeek($currentWeek['monday']),
            'currentWeekOffset'   => $this->currentWeekOffset,
            'pengeluaranPerMinggu'=> $pengeluaranPerMinggu,
            'availableWeeks'      => $this->availableWeeks,
            'hasDataInPreviousWeeks' => $this->hasDataInPreviousWeeks,
            'canGoToPrevious'     => $this->hasDataInWeek($this->currentWeekOffset + 1),
            'canGoToNext'         => $this->currentWeekOffset > 0,
        ]);
    }

    public function openAddModal()
    {
        $this->resetInput();
        $this->showAddModal = true;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetValidation();
        $this->reset(['tanggal', 'nama_item', 'jumlah_pengeluaran']);
    }
}

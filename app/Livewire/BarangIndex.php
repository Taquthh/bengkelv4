<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class BarangIndex extends Component
{
    public $nama, $merk, $tipe, $satuan, $deskripsi, $barangId;
    public $isModalOpen = 0;
    public $stok = 0;

    public $deleteId = null;

    public $showDeleteModal = false;
    
    Public $showDetailModal = false;


    public $viewMode = 'table';

    public $search = '';
    public $sortBy = 'nama';          // default sorting kolom
    public $sortDirection = 'asc';    // default arah sortir

    public function render()
    {
        // Mulai query builder dari model Barang
        $barangs = Barang::select('barangs.*', DB::raw('COALESCE(SUM(pembelians.jumlah_tersisa), 0) as stok'))
            ->leftJoin('pembelians', 'barangs.id', '=', 'pembelians.barang_id')
            ->groupBy('barangs.id')
            ->when($this->search, function ($query) {
                $query->where('barangs.nama', 'like', '%' . $this->search . '%')
                    ->orWhere('barangs.merk', 'like', '%' . $this->search . '%')
                    ->orWhere('barangs.tipe', 'like', '%' . $this->search . '%');
            });

        if ($this->sortBy === 'stok') {
            $barangs->orderBy('stok', $this->sortDirection);
        } else {
            $barangs->orderBy('barangs.' . $this->sortBy, $this->sortDirection);
        }

        $barangs = $barangs->paginate(10);

        return view('livewire.barang-index', compact('barangs'));
    }
    

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    public function create()
    {
        $this->resetInput();
        $this->openModal();
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $this->barangId = $id;
        $this->nama = $barang->nama;
        $this->merk = $barang->merk;
        $this->tipe = $barang->tipe;
        $this->satuan = $barang->satuan;
        $this->deskripsi = $barang->deskripsi;

        $this->openModal(); // <--- Tambahkan ini!
    }



    public function save()
    {
        Barang::updateOrCreate(['id' => $this->barangId], [
            'nama' => $this->nama,
            'merk' => $this->merk,
            'tipe' => $this->tipe,
            'satuan' => $this->satuan,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->closeModal();
        $this->resetInput();
        session()->flash('message', $this->barangId ? 'Barang updated.' : 'Barang created.');
    }

    public function delete()
    {
        if ($this->deleteId) {
            Barang::find($this->deleteId)?->delete();
            session()->flash('message', 'Barang deleted.');
            $this->deleteId = null;
        }
        $this->showDeleteModal = false;
        $this->cancelDelete();
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }



    private function resetInput()
    {
        $this->nama = '';
        $this->merk = '';
        $this->tipe = '';
        $this->satuan = 'pcs';
        $this->deskripsi = '';
        $this->stok = 0; // reset stok juga
        $this->barangId = null;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barang;
use App\Models\Pembelian;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PembelianIndex extends Component
{
    public  $barang_id, $supplier, $harga_beli, $jumlah, $jumlah_tersisa, $tanggal, $keterangan, $pembelianId;
    public $isModalOpen = false;
    public $showDeleteModal = false;
    Public $showDetailModal = false;

    public $deleteId = null;

    public $barangs = [];

    public $viewMode = 'table';

    public $search = '';
    public $sortBy = 'tanggal';
    public $sortDirection = 'desc';
    public $filterSupplier = '';

    public function render()
    {
        $query = Pembelian::with('barang');

        // Pencarian berdasarkan nama, merk, atau tipe barang
        if (!empty($this->search)) {
            $query->whereHas('barang', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('merk', 'like', '%' . $this->search . '%')
                ->orWhere('tipe', 'like', '%' . $this->search . '%');
            });
        }

        // Filter berdasarkan supplier
        if (!empty($this->filterSupplier)) {
            $query->where('supplier', $this->filterSupplier);
        }

        // Sorting
        if (in_array($this->sortBy, ['tanggal', 'harga_beli', 'jumlah'])) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $pembelians = $query->paginate(10);

        // Data pendukung dropdown
        $this->barangs = Barang::all();
        $suppliers = Pembelian::pluck('supplier')->unique()->filter();

        return view('livewire.pembelian-index', [
            'pembelians' => $pembelians,
            'suppliers' => $suppliers,
        ]);
    }


    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    public function create()
    {
        $this->resetInput();
        $this->pembelianId = null; // ⬅ ini penting!
        $this->openModal();
    }


    public function edit($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $this->pembelianId = $id;
        $this->barang_id = $pembelian->barang_id;
        $this->supplier = $pembelian->supplier;
        $this->harga_beli = $pembelian->harga_beli;
        $this->jumlah = $pembelian->jumlah;
        $this->jumlah_tersisa = $pembelian->jumlah_tersisa;
        $this->tanggal = $pembelian->tanggal;
        $this->keterangan = $pembelian->keterangan;
        $this->openModal();
    }

    public function save()
    {
        $this->validate([
            'barang_id' => 'required|exists:barangs,id',
            'harga_beli' => 'required|numeric',
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($this->pembelianId) {
            // EDIT mode
            Pembelian::where('id', $this->pembelianId)->update([
                'barang_id' => $this->barang_id,
                'supplier' => $this->supplier,
                'harga_beli' => $this->harga_beli,
                'jumlah' => $this->jumlah,
                'tanggal' => $this->tanggal ?? now()->toDateString(),
                'keterangan' => $this->keterangan,
                // jumlah_tersisa tidak diubah agar stok FIFO tetap jalan
            ]);
        } else {
            // CREATE mode
            $jumlah = intval($this->jumlah); // memastikan tipe integer
            Pembelian::create([
                'barang_id' => $this->barang_id,
                'supplier' => $this->supplier,
                'harga_beli' => $this->harga_beli,
                'jumlah' => $jumlah,
                'jumlah_tersisa' => $jumlah, // auto set sisa = jumlah awal
                'tanggal' => $this->tanggal ?? now()->toDateString(),
                'keterangan' => $this->keterangan,
            ]);
        }

        session()->flash('message', $this->pembelianId ? 'Data diperbarui.' : 'Pembelian ditambahkan.');
        $this->closeModal();
        $this->resetInput();
    }



    public function delete($id)
    {
        Pembelian::find($id)->delete();
        session()->flash('message', 'Pembelian dihapus.');
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
        $this->barang_id = '';
        $this->supplier = '';
        $this->harga_beli = '';
        $this->jumlah = '';
        $this->jumlah_tersisa = null;
        $this->tanggal = '';
        $this->keterangan = '';
        $this->pembelianId = null;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
}

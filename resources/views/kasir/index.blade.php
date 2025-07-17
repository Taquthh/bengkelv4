<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Halaman Kasir - Transaksi Baru</h2>
    </x-slot>


        <h4>1. Data Mobil</h4>
        <div class="form-group">
            <label>No Polisi</label>
            <input type="text" name="no_polisi" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Merk</label>
            <input type="text" name="merk" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tipe</label>
            <input type="text" name="tipe" class="form-control" required>
        </div>

        <h4 class="mt-4">2. Barang yang Dibeli</h4>
        <div id="barang-list">
            <div class="form-row mb-2">
                <select name="barang[0][id]" class="form-control col">
                    <option value="">-- Pilih Barang --</option>
                    
                </select>
                <input type="number" name="barang[0][jumlah]" class="form-control col" placeholder="Jumlah">
            </div>
        </div>
        <button type="button" onclick="addBarang()" class="btn btn-sm btn-secondary">+ Tambah Barang</button>

        <h4 class="mt-4">3. Jasa yang Digunakan</h4>
        <div id="jasa-list">
            <div class="form-row mb-2">
                <select name="jasa[0][id]" class="form-control col">
                    <option value="">-- Pilih Jasa --</option>
                   
                </select>
                <input type="text" name="jasa[0][keterangan]" class="form-control col" placeholder="Keterangan (opsional)">
            </div>
        </div>
        <button type="button" onclick="addJasa()" class="btn btn-sm btn-secondary">+ Tambah Jasa</button>

        <div class="form-group mt-4">
            <label>Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Transaksi</button>
    </form>
</div>



</x-app-layout>
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembelianSeeder extends Seeder
{
    public function run()
    {
        DB::table('pembelians')->insert([
            [
                'id' => 1,
                'barang_id' => 1,
                'supplier' => 'Perez Ltd',
                'harga_beli' => 43646.39,
                'jumlah' => 41,
                'jumlah_tersisa' => 41,
                'tanggal' => '2025-06-01',
                'keterangan' => 'Order including cell land specific everything window.',
                'created_at' => Carbon::parse('2025-02-26 23:32:05'),
                'updated_at' => Carbon::parse('2025-02-21 04:20:28'),
            ],
            [
                'id' => 2,
                'barang_id' => 1,
                'supplier' => 'Massey, Ortega and Martin',
                'harga_beli' => 14728.29,
                'jumlah' => 27,
                'jumlah_tersisa' => 27,
                'tanggal' => '2025-03-09',
                'keterangan' => 'Design without middle.',
                'created_at' => Carbon::parse('2025-02-28 23:39:15'),
                'updated_at' => Carbon::parse('2025-05-02 03:45:08'),
            ],
            // ... (Lanjutkan dengan data pembelian 3 - 10)
        ]);
    }
}

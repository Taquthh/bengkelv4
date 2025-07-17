<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangSeeder extends Seeder
{
    public function run()
    {
        DB::table('barangs')->insert([
            [
                'id' => 1,
                'nama' => 'Barang 1',
                'merk' => 'Clark, Roberson and Simmons',
                'tipe' => 'design',
                'satuan' => 'pcs',
                'deskripsi' => 'Plan student chance food pull expert time.',
                'created_at' => Carbon::parse('2025-04-16 19:48:15'),
                'updated_at' => Carbon::parse('2025-06-30 12:57:56'),
            ],
            [
                'id' => 2,
                'nama' => 'Barang 2',
                'merk' => 'Short, Reeves and Reese',
                'tipe' => 'worker',
                'satuan' => 'pcs',
                'deskripsi' => 'Teach owner treat white.',
                'created_at' => Carbon::parse('2025-07-06 07:23:53'),
                'updated_at' => Carbon::parse('2025-06-15 17:29:09'),
            ],
            [
                'id' => 3,
                'nama' => 'Barang 3',
                'merk' => 'Brady Inc',
                'tipe' => 'contain',
                'satuan' => 'pcs',
                'deskripsi' => 'Maybe never whole professional mind manage exactly.',
                'created_at' => Carbon::parse('2025-07-09 00:44:16'),
                'updated_at' => Carbon::parse('2025-02-07 05:12:53'),
            ],
            [
                'id' => 4,
                'nama' => 'Barang 4',
                'merk' => 'Davis LLC',
                'tipe' => 'east',
                'satuan' => 'pcs',
                'deskripsi' => 'Beat own explain college.',
                'created_at' => Carbon::parse('2025-06-04 14:02:09'),
                'updated_at' => Carbon::parse('2025-02-03 14:03:34'),
            ],
            [
                'id' => 5,
                'nama' => 'Barang 5',
                'merk' => 'Adams-Yang',
                'tipe' => 'politics',
                'satuan' => 'pcs',
                'deskripsi' => 'Job your star message throughout life.',
                'created_at' => Carbon::parse('2025-01-18 19:19:10'),
                'updated_at' => Carbon::parse('2025-03-31 11:35:06'),
            ],
        ]);
    }
}

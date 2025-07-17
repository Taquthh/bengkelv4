<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'penjualans';

    protected $fillable = [
        'tanggal',
        'kasir',
        'keterangan',
        'total_harga', // Add this field to fillable
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_harga' => 'decimal:2',
    ];

    public function itemPenjualan()
    {
        return $this->hasMany(PenjualanItem::class, 'penjualan_id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(PenjualanItem::class, 'penjualan_id');
    }

    // Hitung total dari item (berguna untuk verifikasi)
    public function hitungTotal()
    {
        return $this->itemPenjualan->sum(function ($item) {
            return $item->jumlah * $item->harga_jual;
        });
    }

    // Dapatkan total terformat
    public function getTotalTerformatAttribute()
    {
        return number_format($this->total_harga, 0, ',', '.');
    }

    // Scope untuk filter berdasarkan rentang tanggal
    public function scopeBerdasarkanRentangTanggal($query, $tanggalMulai, $tanggalSelesai)
    {
        return $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
    }

    // Scope untuk transaksi hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // Dapatkan total penjualan untuk periode tertentu
    public function scopeTotalPenjualan($query, $tanggalMulai = null, $tanggalSelesai = null)
    {
        $query = $query->selectRaw('SUM(total_harga) as total_penjualan');
        
        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }
        
        return $query->first()->total_penjualan ?? 0;
    }
}
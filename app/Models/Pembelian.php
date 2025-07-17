<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id', 'supplier', 'harga_beli', 'jumlah', 'jumlah_tersisa', 'tanggal', 'keterangan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}

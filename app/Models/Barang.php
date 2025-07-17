<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'merk', 'tipe', 'satuan', 'deskripsi'];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

    public function getStokAttribute()
    {
        return $this->pembelians()->sum('jumlah_tersisa');
    }
}

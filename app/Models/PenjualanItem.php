<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id',
        'pembelian_id',
        'barang_id',        // ✅ PASTIKAN INI ADA!
        'jumlah',
        'harga_jual',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_jual' => 'decimal:2',
    ];

    /**
     * Dapatkan transaksi yang memiliki item ini
     */
    public function penjualan()
    {
        return $this->belongsTo(Transaksi::class);
    }

    /**
     * Dapatkan record pembelian dari mana item ini diambil
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    /**
     * Dapatkan produk melalui pembelian
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Dapatkan barang melalui pembelian (relationship indirect)
        */
    public function getSubtotalAttribute()
        {
            return $this->harga_jual * $this->jumlah;
        }

        // Accessor untuk profit
        public function getProfitAttribute()
        {
            $hargaBeli = $this->pembelian ? $this->pembelian->harga_beli : 0;
            return ($this->harga_jual - $hargaBeli) * $this->jumlah;
        }

        // Accessor untuk profit margin (dalam persen)
        public function getProfitMarginAttribute()
        {
            $hargaBeli = $this->pembelian ? $this->pembelian->harga_beli : 0;
            
            if ($hargaBeli <= 0) {
                return 0;
            }
            
            $profitPerUnit = $this->harga_jual - $hargaBeli;
            return round(($profitPerUnit / $hargaBeli) * 100, 2);
        }
}

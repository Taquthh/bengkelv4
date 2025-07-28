<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembelians';
    
    protected $fillable = [
        'barang_id',
        'supplier',
        'tanggal',
        'jumlah',
        'jumlah_tersisa',
        'harga_beli',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'integer',
        'jumlah_tersisa' => 'integer',
        'harga_beli' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function serviceBarangItems()
    {
        return $this->hasMany(ServiceBarangItem::class);
    }

    public function penjualanItems()
    {
        return $this->hasMany(PenjualanItem::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('jumlah_tersisa', '>', 0);
    }

    public function scopeBySupplier($query, $supplier)
    {
        return $query->where('supplier', 'like', '%' . $supplier . '%');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeFifoOrder($query)
    {
        return $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc');
    }

    public function scopeByBarang($query, $barangId)
    {
        return $query->where('barang_id', $barangId);
    }

    // Accessors
    public function getJumlahTerpakaiAttribute()
    {
        return $this->jumlah - $this->jumlah_tersisa;
    }

    public function getPersentaseTerpakaiAttribute()
    {
        if ($this->jumlah == 0) return 0;
        return round(($this->jumlah_terpakai / $this->jumlah) * 100, 2);
    }

    public function getIsHabisAttribute()
    {
        return $this->jumlah_tersisa <= 0;
    }

    public function getStatusStokAttribute()
    {
        if ($this->jumlah_tersisa <= 0) {
            return 'habis';
        } elseif ($this->jumlah_tersisa < ($this->jumlah * 0.2)) {
            return 'sedikit';
        } else {
            return 'tersedia';
        }
    }

    public function getTotalHargaAttribute()
    {
        return $this->jumlah * $this->harga_beli;
    }

    public function getNilaiStokTersisaAttribute()
    {
        return $this->jumlah_tersisa * $this->harga_beli;
    }

    // Mutators
    public function setSupplierAttribute($value)
    {
        $this->attributes['supplier'] = ucwords(strtolower(trim($value)));
    }

    // Methods
    public function kurangiStok($jumlah)
    {
        if ($jumlah > $this->jumlah_tersisa) {
            throw new \Exception("Stok tidak mencukupi. Tersedia: {$this->jumlah_tersisa}, diminta: {$jumlah}");
        }

        if ($jumlah <= 0) {
            throw new \Exception('Jumlah yang dikurangi harus lebih dari 0');
        }

        $this->decrement('jumlah_tersisa', $jumlah);
        return $this;
    }

    public function tambahStok($jumlah)
    {
        if ($jumlah <= 0) {
            throw new \Exception('Jumlah yang ditambah harus lebih dari 0');
        }

        if (($this->jumlah_tersisa + $jumlah) > $this->jumlah) {
            throw new \Exception('Jumlah stok tidak boleh melebihi jumlah pembelian awal');
        }

        $this->increment('jumlah_tersisa', $jumlah);
        return $this;
    }

    public function resetStok()
    {
        $this->update(['jumlah_tersisa' => $this->jumlah]);
        return $this;
    }

    // Static methods
    public static function processStockReduction($barangId, $jumlahDibutuhkan)
    {
        $pembelians = static::where('barang_id', $barangId)
            ->where('jumlah_tersisa', '>', 0)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $usedPembelians = [];
        $sisaKebutuhan = $jumlahDibutuhkan;

        foreach ($pembelians as $pembelian) {
            if ($sisaKebutuhan <= 0) break;

            $jumlahAmbil = min($sisaKebutuhan, $pembelian->jumlah_tersisa);
            
            if ($jumlahAmbil > 0) {
                $usedPembelians[] = [
                    'pembelian_id' => $pembelian->id,
                    'jumlah' => $jumlahAmbil,
                    'harga_beli' => $pembelian->harga_beli,
                    'supplier' => $pembelian->supplier,
                    'tanggal' => $pembelian->tanggal
                ];

                $sisaKebutuhan -= $jumlahAmbil;
            }
        }

        if ($sisaKebutuhan > 0) {
            throw new \Exception("Stok tidak mencukupi. Masih butuh: {$sisaKebutuhan}");
        }

        return $usedPembelians;
    }

    public static function executeStockReduction($usedPembelians)
    {
        foreach ($usedPembelians as $used) {
            $pembelian = static::find($used['pembelian_id']);
            if ($pembelian) {
                $pembelian->kurangiStok($used['jumlah']);
            }
        }
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pembelian) {
            // Set jumlah_tersisa sama dengan jumlah jika belum diset
            if (is_null($pembelian->jumlah_tersisa)) {
                $pembelian->jumlah_tersisa = $pembelian->jumlah;
            }

            // Validate data
            if ($pembelian->jumlah < 0) {
                throw new \Exception('Jumlah pembelian tidak boleh negatif');
            }

            if ($pembelian->harga_beli < 0) {
                throw new \Exception('Harga beli tidak boleh negatif');
            }
        });

        static::updating(function ($pembelian) {
            // Validate jumlah_tersisa tidak boleh negatif
            if ($pembelian->jumlah_tersisa < 0) {
                throw new \Exception('Jumlah tersisa tidak boleh negatif');
            }

            // Validate jumlah_tersisa tidak boleh melebihi jumlah
            if ($pembelian->jumlah_tersisa > $pembelian->jumlah) {
                throw new \Exception('Jumlah tersisa tidak boleh melebihi jumlah pembelian');
            }
        });
    }
}
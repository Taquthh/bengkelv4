<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceBarangItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_barang_items';
    
    protected $fillable = [
        'transaksi_service_id',
        'pembelian_id',
        'barang_id',
        'jumlah',
        'harga_jual',
        'subtotal',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_jual' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function transaksiService()
    {
        return $this->belongsTo(TransaksiService::class);
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Scopes
    public function scopeByBarang($query, $barangId)
    {
        return $query->where('barang_id', $barangId);
    }

    public function scopeByTransaksi($query, $transaksiId)
    {
        return $query->where('transaksi_service_id', $transaksiId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereHas('transaksiService', function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_service', [$startDate, $endDate]);
        });
    }

    public function scopeBySupplier($query, $supplier)
    {
        return $query->whereHas('pembelian', function($q) use ($supplier) {
            $q->where('supplier', 'like', '%' . $supplier . '%');
        });
    }

    // Accessors
    public function getHargaBeliAttribute()
    {
        return $this->pembelian ? $this->pembelian->harga_beli : 0;
    }

    public function getLabaKotorAttribute()
    {
        return $this->subtotal - ($this->harga_beli * $this->jumlah);
    }

    public function getPersentaseLabaAttribute()
    {
        $hargaBeli = $this->harga_beli * $this->jumlah;
        if ($hargaBeli == 0) return 0;
        
        return round(($this->laba_kotor / $hargaBeli) * 100, 2);
    }

    public function getNamaBarangAttribute()
    {
        return $this->barang ? $this->barang->nama : 'Barang Tidak Ditemukan';
    }

    public function getNamaBarangLengkapAttribute()
    {
        return $this->barang ? $this->barang->nama_lengkap : 'Barang Tidak Ditemukan';
    }

    public function getMerkBarangAttribute()
    {
        return $this->barang ? $this->barang->merk : null;
    }

    public function getTipeBarangAttribute()
    {
        return $this->barang ? $this->barang->tipe : null;
    }

    public function getSupplierAttribute()
    {
        return $this->pembelian ? $this->pembelian->supplier : null;
    }

    public function getTanggalPembelianAttribute()
    {
        return $this->pembelian ? $this->pembelian->tanggal : null;
    }

    public function getModalTotalAttribute()
    {
        return $this->harga_beli * $this->jumlah;
    }

    // Mutators
    public function setSubtotalAttribute($value)
    {
        // Auto calculate if not provided
        if (is_null($value) && $this->jumlah && $this->harga_jual) {
            $this->attributes['subtotal'] = $this->jumlah * $this->harga_jual;
        } else {
            $this->attributes['subtotal'] = $value;
        }
    }

    // Methods
    public function retur($jumlahRetur, $alasan = null)
    {
        if ($jumlahRetur <= 0) {
            throw new \Exception('Jumlah retur harus lebih dari 0');
        }

        if ($jumlahRetur > $this->jumlah) {
            throw new \Exception('Jumlah retur tidak boleh melebihi jumlah item');
        }

        // Return stock to pembelian
        if ($this->pembelian) {
            $this->pembelian->increment('jumlah_tersisa', $jumlahRetur);
        }

        // Update item quantities
        if ($jumlahRetur == $this->jumlah) {
            // Delete item if full return
            $this->delete();
        } else {
            // Partial return
            $this->decrement('jumlah', $jumlahRetur);
            $this->subtotal = $this->jumlah * $this->harga_jual;
            $this->save();
        }

        // Update transaksi totals
        $this->transaksiService->hitungTotal();

        return $this;
    }

    public function updateHarga($hargaBaru)
    {
        if ($hargaBaru < 0) {
            throw new \Exception('Harga tidak boleh negatif');
        }

        $this->update([
            'harga_jual' => $hargaBaru,
            'subtotal' => $this->jumlah * $hargaBaru
        ]);

        // Update transaksi totals
        $this->transaksiService->hitungTotal();

        return $this;
    }

    public function updateJumlah($jumlahBaru)
    {
        if ($jumlahBaru <= 0) {
            throw new \Exception('Jumlah harus lebih dari 0');
        }

        $selisih = $jumlahBaru - $this->jumlah;

        // Check stock availability if increasing
        if ($selisih > 0) {
            if (!$this->pembelian || $this->pembelian->jumlah_tersisa < $selisih) {
                throw new \Exception('Stok tidak mencukupi untuk penambahan');
            }
            $this->pembelian->decrement('jumlah_tersisa', $selisih);
        } else if ($selisih < 0) {
            // Return stock if decreasing
            if ($this->pembelian) {
                $this->pembelian->increment('jumlah_tersisa', abs($selisih));
            }
        }

        $this->update([
            'jumlah' => $jumlahBaru,
            'subtotal' => $jumlahBaru * $this->harga_jual
        ]);

        // Update transaksi totals
        $this->transaksiService->hitungTotal();

        return $this;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Validate required fields
            if (!$item->barang_id || !$item->pembelian_id || !$item->transaksi_service_id) {
                throw new \Exception('barang_id, pembelian_id, dan transaksi_service_id wajib diisi');
            }

            // Validate stock availability
            $pembelian = Pembelian::find($item->pembelian_id);
            if (!$pembelian || $pembelian->jumlah_tersisa < $item->jumlah) {
                throw new \Exception('Stok tidak mencukupi');
            }

            // Auto calculate subtotal if not provided
            if (is_null($item->subtotal) && $item->jumlah && $item->harga_jual) {
                $item->subtotal = $item->jumlah * $item->harga_jual;
            }
        });

        static::created(function ($item) {
            // Reduce stock from pembelian
            if ($item->pembelian) {
                $item->pembelian->decrement('jumlah_tersisa', $item->jumlah);
            }

            // Update transaksi totals
            $item->transaksiService->hitungTotal();
        });

        static::updating(function ($item) {
            // Validate jumlah tidak boleh negatif
            if ($item->jumlah < 0) {
                throw new \Exception('Jumlah tidak boleh negatif');
            }

            // Auto recalculate subtotal when jumlah or harga_jual changes
            if ($item->isDirty(['jumlah', 'harga_jual'])) {
                $item->subtotal = $item->jumlah * $item->harga_jual;
            }
        });

        static::updated(function ($item) {
            // Update transaksi totals when item is updated
            $item->transaksiService->hitungTotal();
        });

        static::deleting(function ($item) {
            // Return stock to pembelian when item is deleted
            if ($item->pembelian) {
                $item->pembelian->increment('jumlah_tersisa', $item->jumlah);
            }
        });

        static::deleted(function ($item) {
            // Update transaksi totals when item is deleted
            if ($item->transaksiService) {
                $item->transaksiService->hitungTotal();
            }
        });
    }
}
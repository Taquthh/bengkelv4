<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barangs';
    
    protected $fillable = [
        'nama',
        'merk',
        'tipe',
        'satuan',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
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
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeByMerk($query, $merk)
    {
        return $query->where('merk', 'like', '%' . $merk . '%');
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', 'like', '%' . $tipe . '%');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%')
              ->orWhere('merk', 'like', '%' . $search . '%')
              ->orWhere('tipe', 'like', '%' . $search . '%')
              ->orWhere('deskripsi', 'like', '%' . $search . '%');
        });
    }

    // Accessors
    public function getTotalStokAttribute()
    {
        return $this->pembelians()->sum('jumlah_tersisa');
    }

    public function getAvgHppAttribute()
    {
        return $this->pembelians()
            ->where('jumlah_tersisa', '>', 0)
            ->avg('harga_beli') ?? 0;
    }

    public function getSupplierCountAttribute()
    {
        return $this->pembelians()
            ->where('jumlah_tersisa', '>', 0)
            ->distinct('supplier')
            ->count('supplier');
    }

    public function getNamaLengkapAttribute()
    {
        $parts = array_filter([$this->nama, $this->merk, $this->tipe]);
        return implode(' - ', $parts);
    }

    public function getIsAvailableAttribute()
    {
        return $this->total_stok > 0;
    }

    // Mutators
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = ucwords(strtolower(trim($value)));
    }

    public function setMerkAttribute($value)
    {
        $this->attributes['merk'] = $value ? ucwords(strtolower(trim($value))) : null;
    }

    public function setTipeAttribute($value)
    {
        $this->attributes['tipe'] = $value ? ucwords(strtolower(trim($value))) : null;
    }

    // Methods
    public function getSuppliersWithStock()
    {
        return $this->pembelians()
            ->where('jumlah_tersisa', '>', 0)
            ->select('supplier')
            ->selectRaw('SUM(jumlah_tersisa) as total_stok')
            ->selectRaw('AVG(harga_beli) as harga_beli_rata')
            ->selectRaw('MIN(tanggal) as tanggal_pertama')
            ->selectRaw('MAX(tanggal) as tanggal_terakhir')
            ->groupBy('supplier')
            ->orderBy('tanggal_pertama')
            ->get();
    }

    public function getStokDetailFifo()
    {
        return $this->pembelians()
            ->where('jumlah_tersisa', '>', 0)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function($pembelian) {
                return [
                    'id' => $pembelian->id,
                    'supplier' => $pembelian->supplier,
                    'tanggal' => $pembelian->tanggal,
                    'stok_tersisa' => $pembelian->jumlah_tersisa,
                    'harga_beli' => $pembelian->harga_beli,
                    'total_nilai' => $pembelian->jumlah_tersisa * $pembelian->harga_beli
                ];
            });
    }

    public function checkStokAvailability($jumlahDibutuhkan)
    {
        $totalStok = $this->total_stok;
        
        return [
            'available' => $totalStok >= $jumlahDibutuhkan,
            'stok_tersedia' => $totalStok,
            'kekurangan' => max(0, $jumlahDibutuhkan - $totalStok),
            'suppliers' => $this->getSuppliersWithStock()
        ];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceJasaItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_jasa_items';
    
    protected $fillable = [
        'transaksi_service_id',
        'nama_jasa',
        'harga_jasa',
        'subtotal',
        'keterangan',
    ];

    protected $casts = [
        'harga_jasa' => 'decimal:2',
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

    // Scopes
    public function scopeByTransaksi($query, $transaksiId)
    {
        return $query->where('transaksi_service_id', $transaksiId);
    }

    public function scopeByNamaJasa($query, $namaJasa)
    {
        return $query->where('nama_jasa', 'like', '%' . $namaJasa . '%');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereHas('transaksiService', function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_service', [$startDate, $endDate]);
        });
    }

    public function scopeByHargaRange($query, $minHarga, $maxHarga)
    {
        return $query->whereBetween('harga_jasa', [$minHarga, $maxHarga]);
    }

    // Accessors
    public function getIsJasaStandardAttribute()
    {
        $jasaStandard = [
            'ganti oli',
            'tune up',
            'service rutin',
            'cuci mobil',
            'ganti ban'
        ];

        return in_array(strtolower($this->nama_jasa), $jasaStandard);
    }

    public function getKategoriJasaAttribute()
    {
        $namaLower = strtolower($this->nama_jasa);
        
        if (str_contains($namaLower, 'oli')) return 'ganti_oli';
        if (str_contains($namaLower, 'tune up') || str_contains($namaLower, 'tuneup')) return 'tune_up';
        if (str_contains($namaLower, 'service') || str_contains($namaLower, 'servis')) return 'service_rutin';
        if (str_contains($namaLower, 'mesin')) return 'perbaikan_mesin';
        if (str_contains($namaLower, 'body') || str_contains($namaLower, 'bodi')) return 'perbaikan_body';
        if (str_contains($namaLower, 'elektrik') || str_contains($namaLower, 'listrik')) return 'perbaikan_elektrikal';
        if (str_contains($namaLower, 'cuci') || str_contains($namaLower, 'wash')) return 'cuci_mobil';
        if (str_contains($namaLower, 'ban') || str_contains($namaLower, 'tire')) return 'ganti_ban';
        
        return 'lainnya';
    }

    public function getKategoriJasaLabelAttribute()
    {
        $labels = [
            'ganti_oli' => 'Ganti Oli',
            'tune_up' => 'Tune Up',
            'service_rutin' => 'Service Rutin',
            'perbaikan_mesin' => 'Perbaikan Mesin',
            'perbaikan_body' => 'Perbaikan Body',
            'perbaikan_elektrikal' => 'Perbaikan Elektrikal',
            'cuci_mobil' => 'Cuci Mobil',
            'ganti_ban' => 'Ganti Ban',
            'lainnya' => 'Lainnya'
        ];

        return $labels[$this->kategori_jasa] ?? 'Tidak Diketahui';
    }

    public function getIsExpensiveAttribute()
    {
        return $this->harga_jasa > 200000; // Above 200k considered expensive
    }

    // Mutators
    public function setNamaJasaAttribute($value)
    {
        $this->attributes['nama_jasa'] = ucwords(strtolower(trim($value)));
    }

    public function setSubtotalAttribute($value)
    {
        // Auto calculate if not provided (for jasa, subtotal = harga_jasa)
        if (is_null($value) && $this->harga_jasa) {
            $this->attributes['subtotal'] = $this->harga_jasa;
        } else {
            $this->attributes['subtotal'] = $value;
        }
    }

    public function setHargaJasaAttribute($value)
    {
        $this->attributes['harga_jasa'] = $value;
        // Auto set subtotal when harga_jasa is set
        $this->attributes['subtotal'] = $value;
    }

    // Methods
    public function updateHarga($hargaBaru, $keterangan = null)
    {
        if ($hargaBaru < 0) {
            throw new \Exception('Harga jasa tidak boleh negatif');
        }

        $this->update([
            'harga_jasa' => $hargaBaru,
            'subtotal' => $hargaBaru,
            'keterangan' => $keterangan ? $this->keterangan . "\n" . $keterangan : $this->keterangan
        ]);

        // Update transaksi totals
        $this->transaksiService->hitungTotal();

        return $this;
    }

    public function tambahKeterangan($keterangan)
    {
        $existingKeterangan = $this->keterangan ? $this->keterangan . "\n" : '';
        
        $this->update([
            'keterangan' => $existingKeterangan . $keterangan
        ]);

        return $this;
    }

    // Static methods
    public static function getJasaPopuler($limit = 10)
    {
        return static::selectRaw('nama_jasa, COUNT(*) as total_penggunaan, AVG(harga_jasa) as harga_rata')
            ->groupBy('nama_jasa')
            ->orderBy('total_penggunaan', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getHargaRataJasa($namaJasa)
    {
        return static::where('nama_jasa', 'like', '%' . $namaJasa . '%')
            ->avg('harga_jasa') ?? 0;
    }

    public static function getJasaTermahal($limit = 5)
    {
        return static::with('transaksiService.pelangganMobil')
            ->orderBy('harga_jasa', 'desc')
            ->limit($limit)
            ->get();
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Validate required fields
            if (!$item->transaksi_service_id) {
                throw new \Exception('transaksi_service_id wajib diisi');
            }

            if (!$item->nama_jasa) {
                throw new \Exception('nama_jasa wajib diisi');
            }

            if ($item->harga_jasa < 0) {
                throw new \Exception('harga_jasa tidak boleh negatif');
            }

            // Auto calculate subtotal if not provided
            if (is_null($item->subtotal) && $item->harga_jasa) {
                $item->subtotal = $item->harga_jasa;
            }
        });

        static::created(function ($item) {
            // Update transaksi totals
            $item->transaksiService->hitungTotal();
        });

        static::updating(function ($item) {
            // Auto recalculate subtotal when harga_jasa changes
            if ($item->isDirty(['harga_jasa'])) {
                $item->subtotal = $item->harga_jasa;
            }
        });

        static::updated(function ($item) {
            // Update transaksi totals when item is updated
            $item->transaksiService->hitungTotal();
        });

        static::deleted(function ($item) {
            // Update transaksi totals when item is deleted
            if ($item->transaksiService) {
                $item->transaksiService->hitungTotal();
            }
        });
    }
}
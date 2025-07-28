<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelangganMobil extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pelanggan_mobils';
    
    protected $fillable = [
        'nama_pelanggan',
        'kontak',
        'jenis_pelanggan',
        'nama_perusahaan',
        'merk_mobil',
        'tipe_mobil',
        'nopol',
        'tahun',
        'warna',
        'catatan_mobil',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function transaksiServices()
    {
        return $this->hasMany(TransaksiService::class);
    }

    public function transaksiServicesTerbaru()
    {
        return $this->hasMany(TransaksiService::class)->latest('tanggal_service');
    }

    // Scopes
    public function scopeByNopol($query, $nopol)
    {
        return $query->where('nopol', strtoupper($nopol));
    }

    public function scopeByJenisPelanggan($query, $jenis)
    {
        return $query->where('jenis_pelanggan', $jenis);
    }

    public function scopeByMerk($query, $merk)
    {
        return $query->where('merk_mobil', 'like', '%' . $merk . '%');
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_mobil', 'like', '%' . $tipe . '%');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_pelanggan', 'like', '%' . $search . '%')
              ->orWhere('nopol', 'like', '%' . $search . '%')
              ->orWhere('merk_mobil', 'like', '%' . $search . '%')
              ->orWhere('tipe_mobil', 'like', '%' . $search . '%')
              ->orWhere('kontak', 'like', '%' . $search . '%')
              ->orWhere('nama_perusahaan', 'like', '%' . $search . '%');
        });
    }

    public function scopePerorangan($query)
    {
        return $query->where('jenis_pelanggan', 'perorangan');
    }

    public function scopePerusahaan($query)
    {
        return $query->where('jenis_pelanggan', 'perusahaan');
    }

    // Accessors
    public function getNamaMobilLengkapAttribute()
    {
        $parts = array_filter([$this->merk_mobil, $this->tipe_mobil, $this->tahun]);
        return implode(' ', $parts);
    }

    public function getIsPerusahaanAttribute()
    {
        return $this->jenis_pelanggan === 'perusahaan';
    }

    public function getNamaLengkapAttribute()
    {
        if ($this->is_perusahaan && $this->nama_perusahaan) {
            return $this->nama_perusahaan . ' (' . $this->nama_pelanggan . ')';
        }
        return $this->nama_pelanggan;
    }

    public function getTotalTransaksiAttribute()
    {
        return $this->transaksiServices()->count();
    }

    public function getTotalNilaiTransaksiAttribute()
    {
        return $this->transaksiServices()->sum('total_keseluruhan');
    }

    public function getTotalPiutangAttribute()
    {
        return $this->transaksiServices()->sum('sisa_piutang');
    }

    public function getTransaksiTerakhirAttribute()
    {
        return $this->transaksiServices()->latest('tanggal_service')->first();
    }

    public function getStatusPelangganAttribute()
    {
        $totalTransaksi = $this->total_transaksi;
        $totalNilai = $this->total_nilai_transaksi;
        
        if ($totalTransaksi >= 10 && $totalNilai >= 5000000) {
            return 'vip';
        } elseif ($totalTransaksi >= 5 && $totalNilai >= 2000000) {
            return 'loyal';
        } elseif ($totalTransaksi >= 2) {
            return 'reguler';
        } else {
            return 'baru';
        }
    }

    public function getStatusPelangganLabelAttribute()
    {
        $labels = [
            'vip' => 'VIP',
            'loyal' => 'Loyal',
            'reguler' => 'Reguler',
            'baru' => 'Baru'
        ];

        return $labels[$this->status_pelanggan] ?? 'Tidak Diketahui';
    }

    public function getUmurMobilAttribute()
    {
        if (!$this->tahun) return null;
        return now()->year - $this->tahun;
    }

    public function getKategoriUmurMobilAttribute()
    {
        $umur = $this->umur_mobil;
        
        if (!$umur) return 'tidak_diketahui';
        if ($umur <= 3) return 'baru';
        if ($umur <= 7) return 'sedang';
        if ($umur <= 15) return 'tua';
        return 'sangat_tua';
    }

    // Mutators
    public function setNopolAttribute($value)
    {
        $this->attributes['nopol'] = strtoupper(trim($value));
    }

    public function setMerkMobilAttribute($value)
    {
        $this->attributes['merk_mobil'] = $value ? ucwords(strtolower(trim($value))) : null;
    }

    public function setTipeMobilAttribute($value)
    {
        $this->attributes['tipe_mobil'] = $value ? ucwords(strtolower(trim($value))) : null;
    }

    public function setWarnaAttribute($value)
    {
        $this->attributes['warna'] = $value ? ucwords(strtolower(trim($value))) : null;
    }

    public function setNamaPelangganAttribute($value)
    {
        $this->attributes['nama_pelanggan'] = ucwords(strtolower(trim($value)));
    }

    public function setNamaPerusahaanAttribute($value)
    {
        $this->attributes['nama_perusahaan'] = $value ? ucwords(strtolower(trim($value))) : null;
    }

    public function setKontakAttribute($value)
    {
        // Clean phone number format
        $cleaned = preg_replace('/[^0-9+]/', '', $value);
        $this->attributes['kontak'] = $cleaned ?: null;
    }

    // Methods
    public function getTransaksiPiutang()
    {
        return $this->transaksiServices()
            ->where('sisa_piutang', '>', 0)
            ->orderBy('tanggal_service')
            ->get();
    }

    public function getTransaksiJatuhTempo($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return $this->transaksiServices()
            ->where('jatuh_tempo', '<=', $date)
            ->where('sisa_piutang', '>', 0)
            ->orderBy('jatuh_tempo')
            ->get();
    }

    public function getHistoryService($limit = 10)
    {
        return $this->transaksiServices()
            ->with(['serviceBarangItems.barang', 'serviceJasaItems'])
            ->latest('tanggal_service')
            ->limit($limit)
            ->get();
    }

    public function hitungRataRataTransaksi()
    {
        $transaksis = $this->transaksiServices;
        
        if ($transaksis->isEmpty()) {
            return [
                'rata_nilai' => 0,
                'rata_barang' => 0,
                'rata_jasa' => 0,
                'interval_service' => null
            ];
        }

        return [
            'rata_nilai' => $transaksis->avg('total_keseluruhan'),
            'rata_barang' => $transaksis->avg('total_barang'),
            'rata_jasa' => $transaksis->avg('total_jasa'),
            'interval_service' => $this->hitungIntervalService()
        ];
    }

    private function hitungIntervalService()
    {
        $dates = $this->transaksiServices()
            ->orderBy('tanggal_service')
            ->pluck('tanggal_service')
            ->toArray();

        if (count($dates) < 2) return null;

        $intervals = [];
        for ($i = 1; $i < count($dates); $i++) {
            $intervals[] = $dates[$i]->diffInDays($dates[$i-1]);
        }

        return round(array_sum($intervals) / count($intervals));
    }

    public function prediksiServiceBerikutnya()
    {
        $interval = $this->hitungIntervalService();
        $transaksiTerakhir = $this->transaksi_terakhir;

        if (!$interval || !$transaksiTerakhir) {
            return null;
        }

        return $transaksiTerakhir->tanggal_service->addDays($interval);
    }

    // Static methods
    public static function findByNopol($nopol)
    {
        return static::where('nopol', strtoupper($nopol))->first();
    }

    public static function getPelangganVip($limit = 10)
    {
        return static::withCount('transaksiServices')
            ->withSum('transaksiServices', 'total_keseluruhan')
            ->having('transaksi_services_count', '>=', 10)
            ->orderBy('transaksi_services_sum_total_keseluruhan', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getPelangganPiutang()
    {
        return static::whereHas('transaksiServices', function($query) {
            $query->where('sisa_piutang', '>', 0);
        })->with(['transaksiServices' => function($query) {
            $query->where('sisa_piutang', '>', 0);
        }])->get();
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pelanggan) {
            // Validate nopol format (basic validation)
            if (!preg_match('/^[A-Z]{1,2}\s*\d{1,4}\s*[A-Z]{1,3}$/', $pelanggan->nopol)) {
                // Allow flexible nopol format, just uppercase it
                $pelanggan->nopol = strtoupper($pelanggan->nopol);
            }

            // Set default jenis_pelanggan if not provided
            if (!$pelanggan->jenis_pelanggan) {
                $pelanggan->jenis_pelanggan = 'perorangan';
            }
        });

        static::updating(function ($pelanggan) {
            // Clear nama_perusahaan if jenis_pelanggan is perorangan
            if ($pelanggan->jenis_pelanggan === 'perorangan') {
                $pelanggan->nama_perusahaan = null;
            }
        });
    }
}
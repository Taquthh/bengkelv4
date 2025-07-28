<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransaksiService extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_services';
    
    protected $fillable = [
        'invoice',
        'pelanggan_mobil_id',
        'kasir',
        'tanggal_service',
        'keluhan',
        'diagnosa',
        'pekerjaan_dilakukan',
        'metode_pembayaran',
        'strategi_pembayaran',
        'status_pekerjaan',
        'status_pembayaran',
        'total_barang',
        'total_jasa',
        'total_keseluruhan',
        'total_sudah_dibayar',
        'sisa_pembayaran',
        'jatuh_tempo',
        'no_surat_pesanan',
        'keterangan_piutang',
        'keterangan_pembayaran',
        'status_service',
        'waktu_selesai',
        'waktu_diambil',
    ];

    protected $casts = [
        'tanggal_service' => 'date',
        'jatuh_tempo' => 'date',
        'waktu_selesai' => 'datetime',
        'waktu_diambil' => 'datetime',
        'total_barang' => 'decimal:2',
        'total_jasa' => 'decimal:2',
        'total_keseluruhan' => 'decimal:2',
        'total_sudah_dibayar' => 'decimal:2',
        'sisa_pembayaran' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function pelangganMobil()
    {
        return $this->belongsTo(PelangganMobil::class);
    }

    public function serviceBarangItems()
    {
        return $this->hasMany(ServiceBarangItem::class);
    }

    public function serviceJasaItems()
    {
        return $this->hasMany(ServiceJasaItem::class);
    }

    public function payments()
    {
        return $this->hasMany(ServicePayment::class);
    }

    // Legacy support - will be removed
    public function pembayaranCicilans()
    {
        return $this->payments();
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_service', $status);
    }

    public function scopeByStatusPembayaran($query, $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    public function scopeByStatusPekerjaan($query, $status)
    {
        return $query->where('status_pekerjaan', $status);
    }

    public function scopeByStrategiPembayaran($query, $strategi)
    {
        return $query->where('strategi_pembayaran', $strategi);
    }

    public function scopeByKasir($query, $kasir)
    {
        return $query->where('kasir', 'like', '%' . $kasir . '%');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_service', [$startDate, $endDate]);
    }

    public function scopePiutang($query)
    {
        return $query->where('sisa_pembayaran', '>', 0);
    }

    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopeJatuhTempo($query, $date = null)
    {
        $date = $date ?? now()->toDateString();
        return $query->where('jatuh_tempo', '<=', $date)
                    ->where('sisa_pembayaran', '>', 0);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status_pembayaran', '!=', 'lunas')
                    ->where('jatuh_tempo', '<', now()->toDateString());
    }

    public function scopeDueSoon($query, $days = 7)
    {
        return $query->where('status_pembayaran', '!=', 'lunas')
                    ->whereBetween('jatuh_tempo', [
                        now()->toDateString(),
                        now()->addDays($days)->toDateString()
                    ]);
    }

    // Accessors
    public function getIsLunasAttribute()
    {
        return $this->status_pembayaran === 'lunas' || $this->sisa_pembayaran <= 0;
    }

    public function getIsPiutangAttribute()
    {
        return $this->sisa_pembayaran > 0;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status_pembayaran !== 'lunas' 
               && $this->jatuh_tempo 
               && $this->jatuh_tempo->isPast();
    }

    public function getDaysUntilDueAttribute()
    {
        if (!$this->jatuh_tempo || $this->status_pembayaran === 'lunas') {
            return null;
        }
        
        return now()->diffInDays($this->jatuh_tempo, false);
    }

    public function getCanBePayedAttribute()
    {
        if ($this->status_pembayaran === 'lunas') {
            return false;
        }

        return match($this->strategi_pembayaran) {
            'bayar_akhir' => $this->status_pekerjaan === 'selesai',
            'bayar_dimuka' => $this->status_pekerjaan !== 'belum_dikerjakan',
            'cicilan' => true,
            default => true
        };
    }

    public function getPaymentReadinessMessageAttribute()
    {
        if ($this->status_pembayaran === 'lunas') {
            return 'Pembayaran sudah lunas';
        }

        return match($this->strategi_pembayaran) {
            'bayar_akhir' => $this->status_pekerjaan === 'selesai' 
                ? 'Siap dibayar - pekerjaan sudah selesai' 
                : 'Menunggu pekerjaan selesai untuk pembayaran',
            'bayar_dimuka' => $this->status_pekerjaan !== 'belum_dikerjakan'
                ? 'Siap dibayar - pekerjaan sudah dimulai'
                : 'Menunggu pekerjaan dimulai untuk pembayaran',
            'cicilan' => 'Pembayaran fleksibel - bisa dibayar kapan saja',
            default => 'Status pembayaran tidak jelas'
        };
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp' . number_format($this->total_keseluruhan, 0, ',', '.');
    }

    public function getFormattedPaidAttribute()
    {
        return 'Rp' . number_format($this->total_sudah_dibayar, 0, ',', '.');
    }

    public function getFormattedRemainingAttribute()
    {
        return 'Rp' . number_format($this->sisa_pembayaran, 0, ',', '.');
    }

    // Mutators
    public function setSisaPembayaranAttribute($value)
    {
        $this->attributes['sisa_pembayaran'] = max(0, $value);
    }

    public function setTotalSudahDibayarAttribute($value)
    {
        $this->attributes['total_sudah_dibayar'] = max(0, $value);
    }

    // Methods
    public static function generateInvoice()
    {
        $prefix = 'SVC';
        $date = now()->format('Ymd');
        
        $lastInvoice = static::withTrashed()
            ->where('invoice', 'like', $prefix . $date . '%')
            ->orderBy('invoice', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function hitungTotal()
    {
        $totalBarang = $this->serviceBarangItems()->sum('subtotal');
        $totalJasa = $this->serviceJasaItems()->sum('subtotal');
        $totalKeseluruhan = $totalBarang + $totalJasa;
        
        $this->update([
            'total_barang' => $totalBarang,
            'total_jasa' => $totalJasa,
            'total_keseluruhan' => $totalKeseluruhan,
            'sisa_pembayaran' => $totalKeseluruhan - $this->total_sudah_dibayar
        ]);

        // Update payment status
        $this->updatePaymentStatus();

        return $this;
    }

    public function addPayment($amount, $method, $cashier, $note = null)
    {
        if ($amount <= 0 || $amount > $this->sisa_pembayaran) {
            throw new \InvalidArgumentException('Invalid payment amount');
        }

        if (!$this->can_be_payed) {
            throw new \InvalidArgumentException('Payment not allowed based on current work status and payment strategy');
        }

        $payment = $this->payments()->create([
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => $amount,
            'metode_pembayaran' => $method,
            'keterangan' => $note,
            'kasir' => $cashier,
        ]);

        // Update transaction totals
        $this->increment('total_sudah_dibayar', $amount);
        $this->decrement('sisa_pembayaran', $amount);

        // Update payment status
        $this->updatePaymentStatus();

        return $payment;
    }

    public function updatePaymentStatus()
    {
        if ($this->sisa_pembayaran <= 0) {
            $this->status_pembayaran = 'lunas';
            $this->jatuh_tempo = null;
        } elseif ($this->total_sudah_dibayar > 0) {
            $this->status_pembayaran = 'sebagian';
        } else {
            $this->status_pembayaran = 'belum_bayar';
        }

        $this->save();
        return $this;
    }

    public function updateWorkStatus($status)
    {
        $validStatuses = ['belum_dikerjakan', 'sedang_dikerjakan', 'selesai'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid work status');
        }

        $this->status_pekerjaan = $status;
        
        // Set timestamps based on status
        if ($status === 'selesai' && !$this->waktu_selesai) {
            $this->waktu_selesai = now();
        }
        
        $this->save();
        return $this;
    }

    public function selesaikan($catatan = null)
    {
        $this->update([
            'status_pekerjaan' => 'selesai',
            'status_service' => 'selesai',
            'waktu_selesai' => now(),
            'pekerjaan_dilakukan' => $catatan ? $this->pekerjaan_dilakukan . "\n\n" . $catatan : $this->pekerjaan_dilakukan,
        ]);

        return $this;
    }

    public function ambil($catatan = null)
    {
        $this->update([
            'status_service' => 'diambil',
            'waktu_diambil' => now(),
            'pekerjaan_dilakukan' => $catatan ? $this->pekerjaan_dilakukan . "\n\n" . $catatan : $this->pekerjaan_dilakukan,
        ]);

        return $this;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (empty($transaksi->invoice)) {
                $transaksi->invoice = static::generateInvoice();
            }

            // Set default values
            $transaksi->total_barang = $transaksi->total_barang ?? 0;
            $transaksi->total_jasa = $transaksi->total_jasa ?? 0;
            $transaksi->total_keseluruhan = $transaksi->total_keseluruhan ?? 0;
            $transaksi->total_sudah_dibayar = $transaksi->total_sudah_dibayar ?? 0;
            
            // Auto calculate sisa_pembayaran
            if (is_null($transaksi->sisa_pembayaran)) {
                $transaksi->sisa_pembayaran = $transaksi->total_keseluruhan - $transaksi->total_sudah_dibayar;
            }

            // Set tanggal_service if not provided
            if (is_null($transaksi->tanggal_service)) {
                $transaksi->tanggal_service = now()->toDateString();
            }

            // Set default status
            $transaksi->status_pekerjaan = $transaksi->status_pekerjaan ?? 'belum_dikerjakan';
            $transaksi->strategi_pembayaran = $transaksi->strategi_pembayaran ?? 'bayar_akhir';
        });

        static::updating(function ($transaksi) {
            // Auto update status pembayaran berdasarkan sisa pembayaran
            if ($transaksi->isDirty(['total_keseluruhan', 'total_sudah_dibayar', 'sisa_pembayaran'])) {
                if ($transaksi->sisa_pembayaran <= 0) {
                    $transaksi->status_pembayaran = 'lunas';
                    $transaksi->jatuh_tempo = null;
                } elseif ($transaksi->total_sudah_dibayar > 0 && $transaksi->sisa_pembayaran > 0) {
                    $transaksi->status_pembayaran = 'sebagian';
                } elseif ($transaksi->total_sudah_dibayar <= 0) {
                    $transaksi->status_pembayaran = 'belum_bayar';
                }
            }
        });

        static::deleting(function ($transaksi) {
            // Return stock when transaction is deleted
            foreach ($transaksi->serviceBarangItems as $item) {
                if ($item->pembelian) {
                    $item->pembelian->increment('jumlah_tersisa', $item->jumlah);
                }
            }
        });
    }
}
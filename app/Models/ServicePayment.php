<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_service_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'keterangan',
        'kasir',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
    ];

    /**
     * Get the transaction service that owns the payment.
     */
    public function transaksiService(): BelongsTo
    {
        return $this->belongsTo(TransaksiService::class);
    }

    /**
     * Scope to filter payments by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_bayar', [$startDate, $endDate]);
    }

    /**
     * Scope to filter payments by payment method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('metode_pembayaran', $method);
    }

    /**
     * Scope to filter payments by cashier
     */
    public function scopeByCashier($query, $cashier)
    {
        return $query->where('kasir', $cashier);
    }

    /**
     * Get formatted payment amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    /**
     * Get payment method icon
     */
    public function getMethodIconAttribute()
    {
        return match($this->metode_pembayaran) {
            'tunai' => '💵',
            'transfer' => '🏦',
            default => '💳'
        };
    }
}
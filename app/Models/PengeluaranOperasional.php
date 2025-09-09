<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranOperasional extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_operasional';

    protected $fillable = [
        'tanggal',
        'nama_item',
        'jumlah_pengeluaran',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}

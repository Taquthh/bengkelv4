<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PenjualanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id',
        'pembelian_id',
        'barang_id',
        'jumlah',
        'satuan',
        'harga_jual',
        'nama_barang_manual',
        'harga_beli_manual',
        'keterangan',
        'is_manual',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_jual' => 'decimal:2',
        'harga_beli_manual' => 'decimal:2',
        'is_manual' => 'boolean',
    ];

    /**
     * Validasi data item sebelum disimpan
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Validasi untuk manual items
            if ($item->is_manual) {
                // Manual items harus punya nama_barang_manual dan harga_beli_manual
                if (empty($item->nama_barang_manual)) {
                    throw new \Exception('Nama barang manual wajib diisi');
                }
                if ($item->harga_beli_manual === null || $item->harga_beli_manual < 0) {
                    throw new \Exception('Harga beli manual wajib diisi dengan nilai valid');
                }
                // Set default satuan jika tidak ada
                if (empty($item->satuan)) {
                    $item->satuan = 'pcs';
                }
                // Clear barang_id dan pembelian_id untuk manual items
                $item->barang_id = null;
                $item->pembelian_id = null;
            } else {
                // Regular items harus punya barang_id (pembelian_id optional untuk FIFO)
                if (empty($item->barang_id)) {
                    throw new \Exception('Barang ID wajib diisi untuk item regular');
                }
                // pembelian_id bisa null jika diisi nanti oleh FIFO logic
                
                // Clear manual fields untuk regular items
                $item->nama_barang_manual = null;
                $item->harga_beli_manual = null;
            }

            // Validasi umum
            if ($item->jumlah <= 0) {
                throw new \Exception('Jumlah harus lebih dari 0');
            }
            if ($item->harga_jual < 0) {
                throw new \Exception('Harga jual tidak boleh negatif');
            }
        });
    }

    /**
     * Accessor untuk nama barang (mendukung regular dan manual)
     */
    public function getNamaBarangAttribute()
    {
        if ($this->is_manual) {
            return $this->nama_barang_manual;
        }
        
        return $this->barang ? $this->barang->nama : 'N/A';
    }

    /**
     * Accessor untuk satuan (dengan default fallback)
     */
    public function getSatuanBarangAttribute()
    {
        if ($this->satuan) {
            return $this->satuan;
        }
        
        if (!$this->is_manual && $this->barang) {
            return $this->barang->satuan ?? 'pcs';
        }
        
        return 'pcs';
    }

    /**
     * Scope untuk regular items
     */
    public function scopeRegular($query)
    {
        return $query->where('is_manual', false);
    }

    /**
     * Scope untuk manual items
     */
    public function scopeManual($query)
    {
        return $query->where('is_manual', true);
    }

    /**
     * Scope untuk pencarian item berdasarkan nama (regular atau manual)
     */
    public function scopeSearchByName($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_barang_manual', 'like', '%' . $search . '%')
            ->orWhereHas('barang', function($subQ) use ($search) {
                $subQ->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('merk', 'like', '%' . $search . '%');
            });
        });
    }

    /**
     * Method untuk update item dengan validasi stok
     */
    public function updateWithStockValidation($data)
    {
        DB::beginTransaction();
        
        try {
            // Jika item regular dan barang_id berubah, atau jumlah berubah
            if (!$this->is_manual) {
                // Kembalikan stok lama
                if ($this->pembelian) {
                    $this->pembelian->jumlah_tersisa += $this->jumlah;
                    $this->pembelian->save();
                }
                
                // Validasi stok baru jika barang berbeda atau jumlah berbeda
                if (isset($data['barang_id']) && isset($data['jumlah'])) {
                    $pembelian = \App\Models\Pembelian::where('barang_id', $data['barang_id'])
                        ->where('jumlah_tersisa', '>=', $data['jumlah'])
                        ->orderBy('tanggal', 'asc')
                        ->first();
                    
                    if (!$pembelian) {
                        throw new \Exception('Stok tidak mencukupi untuk barang yang dipilih');
                    }
                    
                    // Update pembelian_id dengan yang baru
                    $data['pembelian_id'] = $pembelian->id;
                    
                    // Kurangi stok baru
                    $pembelian->jumlah_tersisa -= $data['jumlah'];
                    $pembelian->save();
                }
            }
            
            // Update item data
            $this->update($data);
            
            DB::commit();
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Method untuk mendapatkan informasi lengkap item (regular atau manual)
     */
    public function getFullInfoAttribute()
    {
        if ($this->is_manual) {
            return [
                'nama' => $this->nama_barang_manual,
                'satuan' => $this->satuan ?? 'pcs',
                'harga_beli' => $this->harga_beli_manual ?? 0,
                'supplier' => 'MANUAL',
                'type' => 'manual',
                'keterangan' => $this->keterangan ?? '',
            ];
        }
        
        return [
            'nama' => $this->barang ? $this->barang->nama : 'N/A',
            'satuan' => $this->barang ? $this->barang->satuan : 'pcs',
            'harga_beli' => $this->pembelian ? $this->pembelian->harga_beli : 0,
            'supplier' => $this->pembelian ? $this->pembelian->supplier : '-',
            'type' => 'regular',
            'keterangan' => '',
        ];
    }

    /**
     * Method untuk validasi data sebelum update
     */
    public static function validateUpdateData($data, $isManual = false)
    {
        $rules = [
            'jumlah' => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
        ];
        
        if ($isManual) {
            $rules['nama_barang_manual'] = 'required|string|max:255';
            $rules['harga_beli_manual'] = 'required|numeric|min:0';
        } else {
            $rules['barang_id'] = 'required|exists:barangs,id';
        }
        
        $validator = \Validator::make($data, $rules);
        
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        
        return true;
    }

    /**
     * Check if this is a manual item
     */
    public function isManual(): bool
    {
        return $this->is_manual === true;
    }

    /**
     * Check if this is a regular item
     */
    public function isRegular(): bool
    {
        return $this->is_manual === false;
    }

    /**
     * Get supplier info (manual items show "MANUAL")
     */
    public function getSupplierInfoAttribute()
    {
        if ($this->is_manual) {
            return 'MANUAL';
        }
        
        return $this->pembelian ? $this->pembelian->supplier : '-';
    }

    /**
     * Dapatkan transaksi yang memiliki item ini
     */
    public function penjualan()
    {
        return $this->belongsTo(Transaksi::class, 'penjualan_id');
    }

    /**
     * Dapatkan record pembelian dari mana item ini diambil
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    /**
     * Dapatkan produk
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Dapatkan subtotal
     */
    public function getSubtotalAttribute()
    {
        return $this->harga_jual * $this->jumlah;
    }

    /**
     * Accessor untuk profit dengan support manual items
     */
    public function getProfitAttribute()
    {
        if ($this->is_manual) {
            $hargaBeli = $this->harga_beli_manual ?? 0;
            return ($this->harga_jual - $hargaBeli) * $this->jumlah;
        }
        
        $hargaBeli = $this->pembelian ? $this->pembelian->harga_beli : 0;
        return ($this->harga_jual - $hargaBeli) * $this->jumlah;
    }

    /**
     * Accessor untuk profit margin dengan support manual items
     */
    public function getProfitMarginAttribute()
    {
        if ($this->is_manual) {
            $hargaBeli = $this->harga_beli_manual ?? 0;
            
            if ($hargaBeli <= 0) {
                return 100; // 100% profit if no cost
            }
            
            $profitPerUnit = $this->harga_jual - $hargaBeli;
            return round(($profitPerUnit / $hargaBeli) * 100, 2);
        }
        
        $hargaBeli = $this->pembelian ? $this->pembelian->harga_beli : 0;
        
        if ($hargaBeli <= 0) {
            return 0;
        }
        
        $profitPerUnit = $this->harga_jual - $hargaBeli;
        return round(($profitPerUnit / $hargaBeli) * 100, 2);
    }
}
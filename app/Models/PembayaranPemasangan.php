<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranPemasangan extends Model
{
    protected $table = 'pembayaran_pemasangans';

    protected $fillable = [
        'warga_id', 'total_biaya', 'total_dibayar', 'status',
    ];

    /**
     * Relasi ke model Warga.
     */
    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    /**
     * Accessor: Hitung sisa tagihan pemasangan.
     */
    public function getSisaAttribute()
    {
        return $this->total_biaya - $this->total_dibayar;
    }
}

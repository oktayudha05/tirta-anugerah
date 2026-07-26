<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaPemasangan extends Model
{
    protected $table = 'biaya_pemasangans';

    protected $fillable = ['biaya', 'berlaku_mulai', 'is_active'];

    /**
     * Ambil biaya pemasangan yang aktif saat ini (berlaku <= bulan ini).
     */
    public static function getBiayaAktif()
    {
        return self::where('berlaku_mulai', '<=', date('Y-m'))
                   ->orderBy('berlaku_mulai', 'desc')
                   ->first();
    }
}

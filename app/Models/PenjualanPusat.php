<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanPusat extends Model
{
    use HasFactory;

    protected $table = 'penjualan_pusat';

    // TAMBAHKAN BARIS INI
    public $timestamps = false; 

    protected $fillable = [
        'id_jenis',
        'tgl_jual',
        'berat_keluar',
        'harga_jual_pusat',
        'total_pendapatan',
    ];

    public function jenis()
    {
        return $this->belongsTo(JenisSampah::class, 'id_jenis', 'ID_Jenis');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    use HasFactory;

    protected $table = 'jenis_sampah';
    
    // Primary Key disesuaikan dengan struktur database lama Anda
    protected $primaryKey = 'ID_Jenis';

    // Matikan timestamps karena tabel lama Anda tidak memilikinya
    public $timestamps = false;

    protected $fillable = [
        'kategori_id',   // TAMBAHKAN INI: Agar kategori bisa disimpan
        'jenis_sampah',
        'Harga_kg',
    ];

    /**
     * RELASI: Banyak Jenis Sampah dimiliki oleh satu Kategori
     * Ini digunakan untuk menampilkan nama kategori di tabel sampah
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_id', 'id');
    }

    /**
     * RELASI: Satu Jenis Sampah bisa memiliki banyak catatan Setoran
     */
    public function setoran()
    {
        return $this->hasMany(Setoran::class, 'id_jenis', 'ID_Jenis');
    }

    /**
     * RELASI: Satu Jenis Sampah bisa memiliki banyak catatan Penjualan Pusat
     */
    public function penjualanPusat()
    {
        return $this->hasMany(PenjualanPusat::class, 'id_jenis', 'ID_Jenis');
    }
}
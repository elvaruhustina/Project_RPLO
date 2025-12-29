<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSampah extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kategori_sampah';

    // Karena di SQL tadi kita tidak pakai created_at/updated_at secara eksplisit di Laravel, 
    // matikan timestamps jika Anda tidak menambahkannya di tabel.
    // Jika di tabel SQL Anda ada kolom created_at & updated_at, ubah jadi true.
    public $timestamps = false;

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relasi: Satu kategori memiliki banyak jenis sampah.
     * Contoh: Kategori 'Anorganik' punya jenis 'Plastik', 'Logam', dll.
     */
    public function jenisSampah()
    {
        return $this->hasMany(JenisSampah::class, 'kategori_id', 'id');
    }
}
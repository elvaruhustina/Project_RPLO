<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\KategoriSampah; 
use App\Models\Setoran;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisSampahController extends Controller
{
    public function index()
    {
        // Eager loading kategori untuk optimasi query
        $jenis_sampah = JenisSampah::with('kategori')->get();
        return view('jenissampah.index', compact('jenis_sampah'));
    }

    public function create()
    {
        $kategori = KategoriSampah::all();
        return view('jenissampah.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        // Validasi rigor: Menolak angka dan simbol pada jenis_sampah
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategori_sampah,id',
            'jenis_sampah' => [
                'required',
                'string',
                'max:255',
                'unique:jenis_sampah,Jenis_Sampah',
                'regex:/^[a-zA-Z\s]+$/' // Hanya huruf dan spasi
            ],
            'harga_kg'     => 'required|numeric|min:0',
        ], [
            // Pesan error kustom agar lebih user-friendly
            'jenis_sampah.regex' => 'Nama jenis sampah hanya boleh berisi huruf (tidak boleh angka atau simbol).',
            'jenis_sampah.unique' => 'Nama jenis sampah ini sudah terdaftar.',
        ]);

        JenisSampah::create([
            'kategori_id'  => $validated['kategori_id'],
            'jenis_sampah' => $validated['jenis_sampah'],
            'Harga_kg'     => $validated['harga_kg'],
        ]);

        return redirect()->route('jenissampah.index')
            ->with('success', 'Data jenis sampah berhasil ditambahkan.');
    }

    public function show(JenisSampah $jenissampah)
    {
        return view('jenissampah.show', [
            'jenis_sampah' => $jenissampah->load('kategori')
        ]);
    }

    public function edit($id)
    {
        $jenis_sampah = JenisSampah::findOrFail($id);
        $kategori = KategoriSampah::all(); 
        return view('jenissampah.edit', compact('jenis_sampah', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $jenisSampah = JenisSampah::findOrFail($id);

        // Validasi update dengan regex yang sama
        $validated = $request->validate([
            'kategori_id'  => 'sometimes|required|exists:kategori_sampah,id',
            'jenis_sampah' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'unique:jenis_sampah,jenis_sampah,' . $id . ',ID_Jenis',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'harga_kg'     => 'sometimes|required|numeric|min:0',
        ], [
            'jenis_sampah.regex' => 'Nama jenis sampah hanya boleh berisi huruf.',
        ]);

        // Menggunakan Database Transaction untuk integritas saldo nasabah
        DB::transaction(function () use ($request, $jenisSampah, $validated) {
            
            // Logika Update Saldo Nasabah jika Harga berubah
            if ($request->has('harga_kg') && $request->harga_kg != $jenisSampah->Harga_kg) {
                $newHarga = $request->harga_kg;
                $oldHarga = $jenisSampah->Harga_kg;

                $setoranTerkait = Setoran::where('id_jenis', $jenisSampah->ID_Jenis)->get();

                foreach ($setoranTerkait as $s) {
                    $oldTotal = $s->total_harga;
                    $newTotal = $s->total_berat * $newHarga;
                    $selisih  = $newTotal - $oldTotal;

                    // Update Saldo Nasabah secara otomatis
                    Nasabah::where('ID_Nasabah', $s->Id_nasabah)->increment('Total_Saldo', $selisih);
                    
                    // Sinkronisasi total harga di tabel setoran
                    $s->update(['total_harga' => $newTotal]);
                }
            }

            $jenisSampah->update($validated);
        });

        return redirect()->route('jenissampah.index')
            ->with('success', 'Data jenis sampah dan saldo nasabah terkait berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jenisSampah = JenisSampah::findOrFail($id);

        DB::transaction(function () use ($jenisSampah) {
            $setoranTerkait = Setoran::where('id_jenis', $jenisSampah->ID_Jenis)->get();

            foreach ($setoranTerkait as $s) {
                // Kurangi saldo nasabah sebelum data dihapus untuk akurasi keuangan
                Nasabah::where('ID_Nasabah', $s->Id_nasabah)->decrement('Total_Saldo', $s->total_harga);
                $s->delete();
            }

            $jenisSampah->delete();
        });

        return redirect()->route('jenissampah.index')
            ->with('success', 'Data jenis sampah berhasil dihapus dan saldo nasabah telah disesuaikan.');
    }
}
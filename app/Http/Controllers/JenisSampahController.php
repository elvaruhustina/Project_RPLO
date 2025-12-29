<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\KategoriSampah; // Tambahkan import Model Kategori
use App\Models\Setoran;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Digunakan untuk database transaction

class JenisSampahController extends Controller
{
    public function index()
    {
        // Menggunakan eager loading 'kategori' agar performa lebih cepat saat menampilkan data
        $jenis_sampah = JenisSampah::with('kategori')->get();
        return view('jenissampah.index', compact('jenis_sampah'));
    }

    public function create()
    {
        // Mengambil semua data kategori untuk dikirim ke dropdown di View
        $kategori = KategoriSampah::all();
        return view('jenissampah.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategori_sampah,id', // Validasi relasi kategori
            'jenis_sampah' => 'required|string|max:255|unique:jenis_sampah,Jenis_Sampah',
            'harga_kg'     => 'required|numeric|min:0',
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
        $kategori = KategoriSampah::all(); // Kirim data kategori untuk dropdown edit
        return view('jenissampah.edit', compact('jenis_sampah', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $jenisSampah = JenisSampah::findOrFail($id);

        $validated = $request->validate([
            'kategori_id'  => 'sometimes|required|exists:kategori_sampah,id',
            'jenis_sampah' => 'sometimes|required|string|max:255|unique:jenis_sampah,jenis_sampah,' . $id . ',ID_Jenis',
            'harga_kg'     => 'sometimes|required|numeric|min:0',
        ]);

        // Gunakan Database Transaction untuk menjaga integritas data saldo
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

                    // Update Saldo Nasabah
                    Nasabah::where('ID_Nasabah', $s->Id_nasabah)->increment('Total_Saldo', $selisih);
                    
                    // Update Total Harga di baris Setoran
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
                // Kurangi saldo nasabah sebelum setoran dihapus
                Nasabah::where('ID_Nasabah', $s->Id_nasabah)->decrement('Total_Saldo', $s->total_harga);
                $s->delete();
            }

            $jenisSampah->delete();
        });

        return redirect()->route('jenissampah.index')
            ->with('success', 'Data jenis sampah berhasil dihapus dan saldo nasabah telah disesuaikan.');
    }
}
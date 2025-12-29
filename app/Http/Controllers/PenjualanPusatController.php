<?php

namespace App\Http\Controllers;

use App\Models\PenjualanPusat;
use App\Models\JenisSampah;
use App\Models\Setoran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenjualanPusatController extends Controller
{
    public function index()
    {
        $penjualan = PenjualanPusat::with('jenis')->orderBy('tgl_jual', 'desc')->get();
        return view('penjualan.index', compact('penjualan'));
    }

    public function create()
    {
        $jenis_sampah = JenisSampah::all();
        return view('penjualan.create', compact('jenis_sampah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jenis' => 'required|exists:jenis_sampah,ID_Jenis',
            'tgl_jual' => 'required|date',
            'berat_keluar' => 'required|numeric|min:0.01',
            'harga_jual_pusat' => 'required|numeric|min:0',
        ]);

        // LOGIKA PENGURANGAN STOK (VALIDASI)
        $totalMasuk = Setoran::where('id_jenis', $request->id_jenis)->sum('total_berat');
        $totalKeluar = PenjualanPusat::where('id_jenis', $request->id_jenis)->sum('berat_keluar');
        $stokTersedia = $totalMasuk - $totalKeluar;

        if ($request->berat_keluar > $stokTersedia) {
            return back()->with('error', "Stok tidak cukup! Stok tersedia untuk jenis ini hanya " . number_format($stokTersedia, 2) . " Kg.");
        }

        PenjualanPusat::create([
            'id_jenis' => $request->id_jenis,
            'tgl_jual' => $request->tgl_jual,
            'berat_keluar' => $request->berat_keluar,
            'harga_jual_pusat' => $request->harga_jual_pusat,
            'total_pendapatan' => $request->berat_keluar * $request->harga_jual_pusat,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Penjualan ke pusat berhasil dicatat.');
    }

    public function destroy($id)
    {
        $penjualan = PenjualanPusat::findOrFail($id);
        $penjualan->delete();
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus (Stok kembali ke gudang).');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;

class NasabahController extends Controller
{
    public function index()
    {
        $nasabah = Nasabah::all();
        return view('nasabah.index', compact('nasabah'));
    }

    public function create()
    {
        return view('nasabah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // REGEX: /^[a-zA-Z\s]*$/ memastikan hanya huruf dan spasi yang diizinkan
            'nama'     => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'no_induk' => 'required|string|max:50|unique:nasabah,No_Induk',
            'alamat'   => 'nullable|string',
            'no_hp'    => 'nullable|string|max:20',
            'jml_kk'   => 'nullable|integer',
        ], [
            'nama.regex' => 'Nama nasabah hanya boleh berisi huruf dan spasi (tidak boleh angka/simbol).'
        ]);

        Nasabah::create([
            'Nama'     => $validated['nama'],
            'No_Induk' => $validated['no_induk'],
            'Alamat'   => $request->alamat,
            'No_HP'    => $request->no_hp,
            'jml_kk'   => $request->jml_kk,
        ]);

        return redirect()->route('nasabah.index')
            ->with('success', 'Data nasabah berhasil ditambahkan.');
    }

    public function show(Nasabah $nasabah)
    {
        return view('nasabah.show', compact('nasabah'));
    }

    public function edit($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        return view('nasabah.edit', compact('nasabah'));
    }

    public function update(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'sometimes|required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'no_induk' => 'sometimes|required|string|max:50|unique:nasabah,No_Induk,' . $id . ',ID_Nasabah',
            'alamat'   => 'nullable|string',
            'no_hp'    => 'nullable|string|max:20',
            'jml_kk'   => 'nullable|integer',
        ], [
            'nama.regex' => 'Nama nasabah hanya boleh berisi huruf dan spasi.'
        ]);

        // Menggunakan update langsung dari data yang tervalidasi
        $nasabah->update([
            'Nama'     => $request->nama ?? $nasabah->Nama,
            'No_Induk' => $request->no_induk ?? $nasabah->No_Induk,
            'Alamat'   => $request->alamat,
            'No_HP'    => $request->no_hp,
            'jml_kk'   => $request->jml_kk,
        ]);

        return redirect()->route('nasabah.index')
            ->with('success', 'Data nasabah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        $nasabah->delete();

        return redirect()->route('nasabah.index')
            ->with('success', 'Data nasabah berhasil dihapus.');
    }
}
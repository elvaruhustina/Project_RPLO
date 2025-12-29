@extends('layouts.app')

@section('title', 'Tambah Jenis Sampah')

@section('content')
<h1 class="h3 mb-3">Tambah Jenis Sampah</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('jenissampah.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="kategori_id" class="form-label">
            Kategori Sampah <span class="text-danger">*</span>
        </label>
        <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="jenis_sampah" class="form-label">
            Nama Jenis Sampah <span class="text-danger">*</span>
        </label>
        <input type="text"
               name="jenis_sampah"
               id="jenis_sampah"
               class="form-control @error('jenis_sampah') is-invalid @enderror"
               value="{{ old('jenis_sampah') }}"
               placeholder="Contoh: Botol Plastik, Besi Tua, Koran"
               required>
    </div>

    <div class="mb-3">
        <label for="harga_kg" class="form-label">
            Harga per Kg <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number"
                   step="0.01"
                   min="0"
                   name="harga_kg"
                   id="harga_kg"
                   class="form-control @error('harga_kg') is-invalid @enderror"
                   value="{{ old('harga_kg') }}"
                   placeholder="Contoh: 2500"
                   required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('jenissampah.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
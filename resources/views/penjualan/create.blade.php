@extends('layouts.app')

@section('title', 'Catat Penjualan ke Pusat')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Catat Penjualan ke Pusat</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="{{ route('penjualan.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Sampah <span class="text-danger">*</span></label>
                        <select name="id_jenis" id="id_jenis" class="form-select @error('id_jenis') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Sampah --</option>
                            @foreach($jenis_sampah as $js)
                                <option value="{{ $js->ID_Jenis }}" {{ old('id_jenis') == $js->ID_Jenis ? 'selected' : '' }}>
                                    {{ $js->Jenis_Sampah }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Jual <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_jual" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Berat Keluar (Kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="berat_keluar" 
                               class="form-control @error('berat_keluar') is-invalid @enderror" 
                               placeholder="Contoh: 10.50" value="{{ old('berat_keluar') }}" required>
                        <small class="text-muted text-info">* Gunakan titik (.) untuk desimal</small>
                        @error('berat_keluar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Jual/Kg (ke Pusat) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual_pusat" class="form-control" 
                                   placeholder="Harga beli dari pengepul" value="{{ old('harga_jual_pusat') }}" required>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Penjualan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
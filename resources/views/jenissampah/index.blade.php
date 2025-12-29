@extends('layouts.app')

@section('title', 'Jenis Sampah')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Manajemen Jenis Sampah</h1>

    <div class="d-flex justify-content-end mb-3 gap-2">
        <a href="{{ route('kategori.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-list"></i> Kelola Kategori
        </a>
        <a href="{{ route('jenissampah.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jenis Sampah
        </a>
    </div>

    <div class="card w-100 shadow-sm">
        <div class="card-body p-0">
            @if ($jenis_sampah->isEmpty())
                <div class="alert alert-info m-3">
                    Belum ada data jenis sampah. Silakan tambah kategori dan jenis sampah terlebih dahulu.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Kategori</th> <th>Jenis Sampah</th>
                                <th>Harga per Kg</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenis_sampah as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ $item->Jenis_Sampah }}</td>
                                    <td>Rp {{ number_format($item->Harga_kg, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('jenissampah.show', $item->ID_Jenis) }}" class="btn btn-info btn-sm">Detail</a>
                                        <a href="{{ route('jenissampah.edit', $item->ID_Jenis) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('jenissampah.destroy', $item->ID_Jenis) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Menghapus jenis sampah akan menghapus data setoran terkait. Lanjutkan?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
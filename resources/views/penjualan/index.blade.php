@extends('layouts.app')
@section('title', 'Penjualan ke Pusat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Riwayat Penjualan ke Pusat (Outflow)</h1>
        <a href="{{ route('penjualan.create') }}" class="btn btn-success">
            <i class="fas fa-truck-loading"></i> Catat Penjualan Baru
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Sampah</th>
                            <th>Berat Keluar</th>
                            <th>Harga Jual/Kg</th>
                            <th>Total Pendapatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $p)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($p->tgl_jual)->format('d/m/Y') }}</td>
                            <td>{{ $p->jenis->Jenis_Sampah }}</td>
                            <td>{{ $p->berat_keluar }} Kg</td>
                            <td>Rp {{ number_format($p->harga_jual_pusat) }}</td>
                            <td class="fw-bold text-success">Rp {{ number_format($p->total_pendapatan) }}</td>
                            <td>
                                <form action="{{ route('penjualan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data? Stok akan dikembalikan ke gudang.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Batal Jual</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada transaksi penjualan ke pusat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
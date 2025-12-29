<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - BSU Hidayah Geneva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        .wrapper { display: flex; }
        .sidebar { 
            width: 260px; 
            min-height: 100vh; 
            background: #2c3e50; 
            color: white; 
            flex-shrink: 0; 
            position: fixed;
        }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); margin: 5px 15px; border-radius: 5px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #34495e; color: white; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .main-content { margin-left: 260px; width: calc(100% - 260px); min-height: 100vh; background: #f8f9fa; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar shadow">
        <div class="sidebar-header text-center">
            <h5 class="fw-bold">BSU Geneva</h5>
            <small class="text-info">Admin Panel</small>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/nasabah" class="nav-link {{ request()->is('nasabah*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Data Nasabah
                </a>
            </li>
            
            <li class="nav-item">
                <hr class="mx-3 opacity-25">
                <small class="mx-4 text-uppercase opacity-50" style="font-size: 10px;">Logistik & Stok</small>
            </li>

            <li class="nav-item">
                <a href="/kategori" class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i> Kategori Sampah
                </a>
            </li>
            <li class="nav-item">
                <a href="/jenissampah" class="nav-link {{ request()->is('jenissampah*') ? 'active' : '' }}">
                    <i class="fas fa-recycle me-2"></i> Jenis Sampah
                </a>
            </li>
            <li class="nav-item">
                <a href="/setoran" class="nav-link {{ request()->is('setoran*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-heart me-2"></i> Setoran Nasabah
                </a>
            </li>
            <li class="nav-item">
                <a href="/penjualan" class="nav-link {{ request()->is('penjualan*') ? 'active' : '' }}">
                    <i class="fas fa-truck-moving me-2"></i> Penjualan ke Pusat
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm mb-4 px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-text fw-bold">Sistem Keuangan Bank Sampah Hidayah Geneva</span>
                <div class="dropdown">
                    <a class="dropdown-toggle text-decoration-none text-dark" data-bs-toggle="dropdown" href="#">
                        <i class="fas fa-user-circle fa-lg me-1"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Keluar dari sistem?')">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
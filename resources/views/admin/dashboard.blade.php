@extends('admin.layout')

@section('title', 'Dashboard Absensi')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Dashboard</p>
        <h3 class="fw-bold mb-0">Admin Operator Absensi</h3>
    </div>
    <div class="card-panel px-3 py-2">
        <small class="text-muted">{{ $settings->company_name }}</small>
    </div>
</div>

<div class="menu-grid">
    <a href="{{ route('admin.locations') }}" class="menu-card">
        <div class="icon">📍</div>
        <h6 class="fw-bold">Lokasi</h6>
        <small class="text-muted">Kelola titik absensi</small>
    </a>
    <a href="{{ route('admin.employees') }}" class="menu-card">
        <div class="icon">👤</div>
        <h6 class="fw-bold">Pegawai</h6>
        <small class="text-muted">Lihat data pegawai</small>
    </a>
    <a href="{{ route('admin.reports') }}" class="menu-card">
        <div class="icon">📈</div>
        <h6 class="fw-bold">Laporan</h6>
        <small class="text-muted">Riwayat absensi</small>
    </a>
    <a href="{{ route('admin.settings') }}" class="menu-card">
        <div class="icon">⚙️</div>
        <h6 class="fw-bold">Pengaturan</h6>
        <small class="text-muted">Atur aplikasi</small>
    </a>
</div>

<div class="stats-grid">
    <div class="card-panel stat-card">
        <div class="stat-label">Lokasi Aktif</div>
        <div class="stat-value">{{ $locations->count() }}</div>
    </div>
    <div class="card-panel stat-card">
        <div class="stat-label">Pegawai</div>
        <div class="stat-value">{{ $employees->count() }}</div>
    </div>
    <div class="card-panel stat-card">
        <div class="stat-label">Absensi Hari Ini</div>
        <div class="stat-value">{{ $attendanceLogs->count() }}</div>
    </div>
    <div class="card-panel stat-card">
        <div class="stat-label">Perusahaan</div>
        <div class="stat-value">{{ $settings->company_name }}</div>
    </div>
</div>

<div class="card-panel p-4">
    <h5 class="fw-bold mb-3">Aktivitas Terbaru</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Pegawai</th>
                    <th>Tanggal</th>
                    <th>Masuk</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceLogs as $log)
                    <tr>
                        <td>{{ $log->user?->name ?? '-' }}</td>
                        <td>{{ $log->tanggal?->format('Y-m-d') ?? '-' }}</td>
                        <td>{{ $log->jam_masuk ?? '-' }}</td>
                        <td><span class="badge bg-success">{{ $log->status }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

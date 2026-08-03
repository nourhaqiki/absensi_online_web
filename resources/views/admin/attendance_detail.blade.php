@extends('admin.layout')

@section('title', 'Detail Absensi')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Absensi</p>
        <h3 class="fw-bold mb-0">Detail Absensi</h3>
        <p class="text-muted">NIP: {{ $presensi->user?->nip ?? '-' }} · Nama: {{ $presensi->user?->name ?? '-' }}</p>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

<div class="card-panel p-4 mb-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="p-4 rounded-4 bg-white border">
                <h6 class="fw-semibold">Informasi Pegawai</h6>
                <p class="mb-1"><strong>Nama:</strong> {{ $presensi->user?->name ?? '-' }}</p>
                <p class="mb-1"><strong>NIP:</strong> {{ $presensi->user?->nip ?? '-' }}</p>
                <p class="mb-1"><strong>Jabatan:</strong> {{ $presensi->user?->position ?? '-' }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 rounded-4 bg-white border">
                <h6 class="fw-semibold">Detail Absensi</h6>
                <p class="mb-1"><strong>Tanggal:</strong> {{ $presensi->tanggal?->format('Y-m-d') ?? '-' }}</p>
                <p class="mb-1"><strong>Jam Masuk:</strong> {{ $presensi->jam_masuk ?? '-' }}</p>
                <p class="mb-1"><strong>Jam Pulang:</strong> {{ $presensi->jam_pulang ?? '-' }}</p>
                <p class="mb-1"><strong>Status:</strong> {{ $presensi->status ?? '-' }}</p>
                <p class="mb-0"><strong>Catatan:</strong> {{ $presensi->notes ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card-panel p-4 mb-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="p-4 rounded-4 bg-white border">
                <h6 class="fw-semibold">Lokasi</h6>
                <p class="mb-1"><strong>Nama Lokasi:</strong> {{ $presensi->location?->name ?? '-' }}</p>
                <p class="mb-1"><strong>Latitude:</strong> {{ $presensi->location?->latitude ?? ($presensi->lat_masuk ?? '-') }}</p>
                <p class="mb-1"><strong>Longitude:</strong> {{ $presensi->location?->longitude ?? ($presensi->long_masuk ?? '-') }}</p>
                <p class="mb-0"><strong>Alamat:</strong> {{ $presensi->location?->address ?? '-' }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 rounded-4 bg-white border">
                <h6 class="fw-semibold">Foto Selfie</h6>
                <div class="d-flex flex-column gap-3">
                    <div>
                        <p class="mb-1"><strong>Foto Masuk:</strong></p>
                        @if($presensi->foto_masuk_url)
                            <img src="{{ $presensi->foto_masuk_url }}" class="img-fluid rounded-4" alt="Foto Masuk">
                        @else
                            <div class="text-muted">Tidak ada foto masuk</div>
                        @endif
                    </div>
                    <div>
                        <p class="mb-1"><strong>Foto Pulang:</strong></p>
                        @if($presensi->foto_pulang_url)
                            <img src="{{ $presensi->foto_pulang_url }}" class="img-fluid rounded-4" alt="Foto Pulang">
                        @else
                            <div class="text-muted">Tidak ada foto pulang</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

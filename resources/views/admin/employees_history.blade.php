@extends('admin.layout')

@section('title', 'Riwayat Absensi Pegawai')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Pegawai</p>
        <h3 class="fw-bold mb-0">Riwayat Absensi {{ $employee->name }}</h3>
        <p class="text-muted">ID: {{ $employee->id }} · NIP: {{ $employee->nip }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.employees') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
        <a href="{{ route('admin.employees.history.export', $employee) }}" class="btn btn-primary rounded-3">Download CSV</a>
    </div>
</div>

<div class="card-panel p-4 mb-4">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-4 rounded-4 bg-white border">
                <h6 class="fw-semibold">Status Absensi Hari Ini</h6>
                @php
                    $todayStatus = $attendanceLogs->first();
                @endphp
                <p class="display-6 fw-bold mt-3">{{ $todayStatus?->status ?? 'Belum Hadir' }}</p>
                <p class="text-muted">Terakhir: {{ $todayStatus?->tanggal?->format('Y-m-d') ?? '-' }} {{ $todayStatus?->jam_masuk ?? '' }}</p>
            </div>
        </div>
        <div class="col-md-8">
            <div class="p-4 rounded-4 bg-white border">
                <h6 class="fw-semibold">Ringkasan</h6>
                <div class="d-flex justify-content-between mt-3">
                    <div>
                        <p class="text-muted mb-1">Total Absen</p>
                        <p class="fw-bold">{{ $attendanceLogs->count() }}</p>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Jam Masuk Terakhir</p>
                        <p class="fw-bold">{{ $todayStatus?->jam_masuk ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-panel p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceLogs as $log)
                    <tr>
                        <td>{{ $log->tanggal?->format('Y-m-d') ?? '-' }}</td>
                        <td>{{ $log->jam_masuk ?? '-' }}</td>
                        <td>{{ $log->jam_pulang ?? '-' }}</td>
                        <td>{{ $log->location?->name ?? '-' }}</td>
                        <td>{{ $log->location?->latitude ?? ($log->lat_masuk ?? '-') }}</td>
                        <td>{{ $log->location?->longitude ?? ($log->long_masuk ?? '-') }}</td>
                        <td>{{ $log->status ?? '-' }}</td>
                        <td>{{ $log->notes ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.attendance.detail', $log) }}" class="btn btn-sm btn-outline-primary rounded-3">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('admin.layout')

@section('title', 'Laporan')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Laporan</p>
        <h3 class="fw-bold mb-0">Riwayat Absensi</h3>
    </div>
    <a href="{{ route('admin.reports.export') }}" class="btn btn-primary rounded-3 px-4">Export CSV</a>
</div>

<div class="card-panel p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Foto Masuk</th>
                    <th>Foto Pulang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceLogs as $log)
                    <tr>
                        <td>{{ $log->user?->nip ?? '-' }}</td>
                        <td>{{ $log->user?->name ?? '-' }}</td>
                        <td>{{ $log->tanggal?->format('Y-m-d') ?? '-' }}</td>
                        <td>{{ $log->jam_masuk ?? '-' }}</td>
                        <td>{{ $log->jam_pulang ?? '-' }}</td>
                        <td>{{ $log->location?->name ?? '-' }}</td>
                        <td>{{ $log->location?->latitude ?? ($log->lat_masuk ?? '-') }}</td>
                        <td>{{ $log->location?->longitude ?? ($log->long_masuk ?? '-') }}</td>
                        <td><span class="badge bg-{{ $log->status === 'present' ? 'success' : 'secondary' }}">{{ $log->status ?? '-' }}</span></td>
                        <td>{{ $log->notes ?? '-' }}</td>
                        <td>
                            @if($log->foto_masuk_url)
                                <img src="{{ $log->foto_masuk_url }}" class="img-thumbnail" style="max-width:80px; max-height:80px;" alt="Foto Masuk">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($log->foto_pulang_url)
                                <img src="{{ $log->foto_pulang_url }}" class="img-thumbnail" style="max-width:80px; max-height:80px;" alt="Foto Pulang">
                            @else
                                -
                            @endif
                        </td>
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

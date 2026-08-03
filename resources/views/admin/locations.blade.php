@extends('admin.layout')

@section('title', 'Lokasi')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Lokasi</p>
        <h3 class="fw-bold mb-0">Daftar Titik Absensi</h3>
    </div>
    <a href="{{ route('admin.locations.create') }}" class="btn btn-primary rounded-3">Tambah Lokasi</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card-panel p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Radius</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locations as $location)
                    <tr>
                        <td>{{ $location->name }}</td>
                        <td>{{ $location->address }}</td>
                        <td>{{ $location->latitude ?? '-' }}</td>
                        <td>{{ $location->longitude ?? '-' }}</td>
                        <td>{{ $location->radius_meter }} m</td>
                        <td><span class="badge bg-{{ $location->active ? 'success' : 'secondary' }}">{{ $location->active ? 'Aktif' : 'Non Aktif' }}</span></td>
                        <td>
                            <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-sm btn-outline-primary rounded-3 me-1">Edit</a>
                            <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lokasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-sA+e2KxR7AC/2hL2s2UZ2XRgU9eF5MfM7h0K6o8uF3s=" crossorigin=""/>
@endpush


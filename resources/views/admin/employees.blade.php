@extends('admin.layout')

@section('title', 'Pegawai')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Pegawai</p>
        <h3 class="fw-bold mb-0">Data Pegawai</h3>
    </div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary rounded-3 px-4">+ Tambah Pegawai</a>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-3">{{ session('success') }}</div>
@endif

<div class="card-panel p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Daftar Pegawai</h5>
        <span class="text-muted small">{{ count($employees) }} data</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Hari Ini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td>
                            @php
                                $photoPath = $employee->photo ? asset('storage/' . $employee->photo) : null;
                            @endphp
                            @if($photoPath)
                                <img src="{{ $photoPath }}" alt="Foto {{ $employee->name }}" class="rounded-circle" width="44" height="44" style="object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:44px; height:44px;">N/A</div>
                            @endif
                        </td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->nip }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->email }}</td>
                        <td><span class="badge rounded-pill bg-success-subtle text-success">{{ $employee->active ? 'Aktif' : 'Non Aktif' }}</span></td>
                        <td>
                            @php
                                $todayLog = $employee->attendanceLogs->first();
                            @endphp
                            @if($todayLog)
                                <span class="badge rounded-pill bg-info-subtle text-info">{{ $todayLog->status }}</span>
                            @else
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.employees.history', $employee) }}" class="btn btn-sm btn-outline-primary rounded-3">Riwayat</a>
                                <a href="{{ route('admin.employees.show', $employee) }}" class="btn btn-sm btn-outline-info rounded-3">View</a>
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-outline-primary rounded-3">Edit</a>
                                <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Hapus pegawai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-3">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@foreach($employees as $employee)
<div class="modal fade" id="editEmployee{{ $employee->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4">
            <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
                @csrf
                @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ $employee->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP</label>
                            <input type="text" name="nip" class="form-control rounded-3" value="{{ $employee->nip }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" name="position" class="form-control rounded-3" value="{{ $employee->position }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control rounded-3" value="{{ $employee->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3" value="{{ $employee->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="active" class="form-select rounded-3">
                                <option value="1" {{ $employee->active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$employee->active ? 'selected' : '' }}>Non Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary rounded-3">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

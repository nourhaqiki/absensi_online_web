@extends('admin.layout')

@section('title', 'Detail Pegawai')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Pegawai</p>
        <h3 class="fw-bold mb-0">Detail Pegawai</h3>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-outline-primary rounded-3">Edit</a>
        <a href="{{ route('admin.employees') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
    </div>
</div>

<div class="card-panel p-4">
    <div class="row g-4 align-items-center">
        <div class="col-md-3 text-center">
            @if($employee->photo)
                <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto {{ $employee->name }}" class="rounded-circle border" width="140" height="140" style="object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
            @else
                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto" style="width:140px; height:140px;">No Photo</div>
            @endif
        </div>
        <div class="col-md-9">
            <h4 class="fw-bold mb-3">{{ $employee->name }}</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-1 text-muted">NIP</p>
                    <p class="fw-semibold">{{ $employee->nip ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Jabatan</p>
                    <p class="fw-semibold">{{ $employee->position ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Phone</p>
                    <p class="fw-semibold">{{ $employee->phone ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Email</p>
                    <p class="fw-semibold">{{ $employee->email ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Status</p>
                    <p class="fw-semibold">{{ $employee->active ? 'Aktif' : 'Non Aktif' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

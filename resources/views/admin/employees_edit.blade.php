@extends('admin.layout')

@section('title', 'Edit Pegawai')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Pegawai</p>
        <h3 class="fw-bold mb-0">Edit Pegawai</h3>
    </div>
    <a href="{{ route('admin.employees') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

<div class="card-panel p-4">
    <form method="POST" action="{{ route('admin.employees.update', $employee) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                <label class="form-label fw-semibold">Password (ubah)</label>
                <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak ingin mengubah password">
                <div class="form-text">Password tidak ditampilkan demi keamanan. Masukkan nilai baru untuk mengganti password akun (jika ada) atau membuat akun baru jika belum ada.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                <select name="active" class="form-select rounded-3">
                    <option value="1" {{ $employee->active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$employee->active ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Foto</label>
                <input type="file" name="photo" class="form-control rounded-3" accept="image/*">
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Foto saat ini" class="mt-3 rounded-circle" width="80" height="80" style="object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
                @endif
            </div>
            <div class="col-12">
                <button class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection

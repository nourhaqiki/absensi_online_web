@extends('admin.layout')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Pegawai</p>
        <h3 class="fw-bold mb-0">Tambah Pegawai</h3>
    </div>
    <a href="{{ route('admin.employees') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

<div class="card-panel p-4">
    <form method="POST" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama</label>
                <input type="text" name="name" class="form-control rounded-3" placeholder="Masukkan nama pegawai" value="{{ old('name') }}" required>
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">NIP</label>
                <input type="text" name="nip" class="form-control rounded-3" placeholder="NIP" value="{{ old('nip') }}">
                @error('nip') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Jabatan</label>
                <input type="text" name="position" class="form-control rounded-3" placeholder="Jabatan" value="{{ old('position') }}">
                @error('position') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Phone</label>
                <input type="text" name="phone" class="form-control rounded-3" placeholder="No. handphone" value="{{ old('phone') }}">
                @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control rounded-3" placeholder="Email" value="{{ old('email') }}">
                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Password (untuk login)</label>
                <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak membuat akun">
                @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="form-text">Jika diisi, akan membuat akun user (email + password) untuk login aplikasi.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                <select name="active" class="form-select rounded-3">
                    <option value="1" {{ old('active', '1') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('active') === '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Foto</label>
                <input type="file" name="photo" class="form-control rounded-3" accept="image/*">
            </div>
            <div class="col-12">
                <button class="btn btn-primary rounded-3 px-4">Simpan Pegawai</button>
            </div>
        </div>
    </form>
</div>
@endsection

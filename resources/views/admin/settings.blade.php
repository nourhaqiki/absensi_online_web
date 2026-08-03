@extends('admin.layout')

@section('title', 'Pengaturan')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Pengaturan</p>
        <h3 class="fw-bold mb-0">Konfigurasi Aplikasi</h3>
    </div>
</div>

<div class="card-panel p-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.settings.save') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Aplikasi</label>
            <input type="text" name="app_name" class="form-control" value="{{ old('app_name', $settings->app_name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Perusahaan</label>
            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $settings->company_name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Zona Waktu</label>
            <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $settings->timezone) }}">
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="attendance_required_photo" value="1" {{ old('attendance_required_photo', $settings->attendance_required_photo) ? 'checked' : '' }}>
            <label class="form-check-label">Wajib foto saat absensi</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="attendance_required_location" value="1" {{ old('attendance_required_location', $settings->attendance_required_location) ? 'checked' : '' }}>
            <label class="form-check-label">Wajib lokasi saat absensi</label>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection

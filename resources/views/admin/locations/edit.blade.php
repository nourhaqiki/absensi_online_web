@extends('admin.layout')

@section('title', 'Edit Lokasi')

@section('content')
<div class="page-header">
    <div>
        <p class="eyebrow">Lokasi</p>
        <h3 class="fw-bold mb-0">Edit Lokasi</h3>
    </div>
    <a href="{{ route('admin.locations') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

<div class="card-panel p-4">
    <form method="POST" action="{{ route('admin.locations.update', $location) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Lokasi</label>
                <input type="text" name="name" class="form-control rounded-3" placeholder="Masukkan nama lokasi" value="{{ old('name', $location->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Alamat (opsional)</label>
                <input type="text" name="address" class="form-control rounded-3" placeholder="Alamat lokasi" value="{{ old('address', $location->address) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-control rounded-3" value="{{ old('latitude', $location->latitude) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-control rounded-3" value="{{ old('longitude', $location->longitude) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Radius (meter)</label>
                <input type="number" name="radius_meter" class="form-control rounded-3" value="{{ old('radius_meter', $location->radius_meter) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                <select name="active" class="form-select rounded-3">
                    <option value="1" {{ old('active', $location->active) ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !old('active', $location->active) ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-12">
                <div id="location-map" style="height: 420px; border-radius: 16px; overflow: hidden;"></div>
                <p class="text-muted small mt-2">Klik titik pada peta untuk memilih koordinat lokasi.</p>
            </div>
            <div class="col-12">
                <button class="btn btn-primary rounded-3 px-4">Perbarui Lokasi</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-sA+e2KxR7AC/2hL2s2UZ2XRgU9eF5MfM7h0K6o8uF3s=" crossorigin=""/>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-Vxj9x2e6uW1E5Y2nACxwprhacDzEWVsZRf6XUtm0GDo=" crossorigin=""></script>
    <script>
        const map = L.map('location-map').setView([{{ $location->latitude ?? -6.2 }}, {{ $location->longitude ?? 106.816666 }}], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker;
        function updateCoordinates(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], {draggable: true}).addTo(map);
                marker.on('moveend', function (event) {
                    const pos = event.target.getLatLng();
                    updateCoordinates(pos.lat, pos.lng);
                });
            }
        }

        map.on('click', function (event) {
            updateCoordinates(event.latlng.lat, event.latlng.lng);
        });

        const initialLat = document.getElementById('latitude').value;
        const initialLng = document.getElementById('longitude').value;
        if (initialLat && initialLng) {
            const lat = parseFloat(initialLat);
            const lng = parseFloat(initialLng);
            map.setView([lat, lng], 14);
            updateCoordinates(lat, lng);
        }
    </script>
@endpush

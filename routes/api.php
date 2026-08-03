
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\UserController;
use App\Models\Location;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route untuk cek kesehatan API, biasanya dipakai untuk memastikan server aktif
Route::get('/health', function () {
    return response()->json(['status' => 'ok']); // mengembalikan respons JSON sederhana
});

// Menyediakan respon GET untuk /api/login agar jika dibuka di browser tidak muncul error MethodNotAllowed
// Ini bersifat informatif untuk pengguna yang membuka URL via browser
Route::get('/login', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Use POST /api/login with email/nip and password to obtain token.',
        'data' => null,
    ], 405); // mengembalikan status HTTP 405 Method Not Allowed
});

// Endpoint login dengan metode POST, diproses oleh AuthController@login
Route::post('/login', [AuthController::class, 'login']);

// Endpoint logout dengan metode POST, menggunakan middleware auth:sanctum agar hanya user terautentikasi yang bisa mengakses
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Grup route yang dilindungi oleh middleware auth:sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Endpoint absensi, diproses oleh PresensiController@store
    // Biasanya digunakan untuk melakukan presensi masuk/keluar
    Route::post('/absen', [PresensiController::class, 'store']);

    // Endpoint riwayat absen user, dapat menampilkan data presensi user yang sedang login
    Route::get('/riwayat-absen', [PresensiController::class, 'history']);

    // Alias endpoint riwayat user, juga diarahkan ke PresensiController@history
    Route::get('/user/history', [PresensiController::class, 'history']);

    // Endpoint /user untuk mendapatkan data user yang sedang login
    // Masih berada dalam middleware auth:sanctum, jadi hanya user yang sudah login yang bisa mengakses
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user(); // mengembalikan data user dari request yang sudah terautentikasi
    });

    // Endpoint untuk melihat profil user yang login, diproses oleh UserController@profile
    Route::get('/user-profile', [UserController::class, 'profile']);

    // Endpoint untuk update data user, diproses oleh UserController@update
    Route::post('/user-update', [UserController::class, 'update']);
});

// Endpoint publik untuk mendapatkan daftar lokasi
// Mengambil semua lokasi terbaru berdasarkan kolom created_at/updated_at
Route::get('/locations', function () {
    return response()->json(Location::latest()->get());
});

// Endpoint publik untuk mendapatkan daftar karyawan
// Memfilter user yang memiliki nilai nip tidak null, lalu mengurutkan dari terbaru
Route::get('/employees', function () {
    return response()->json(User::whereNotNull('nip')->latest()->get());
});

// Endpoint publik untuk mendapatkan data presensi lengkap
// Termasuk relasi user dan lokasi pada setiap presensi
Route::get('/attendance', function () {
    return response()->json(Presensi::with(['user', 'location'])->latest()->get());
});

<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('admin.login');
})->name('login');

Route::post('/login', function (Request $request) {
    if ($request->input('username') === 'admin' && $request->input('password') === 'admin') {
        Session::put('admin_logged_in', true);
        Session::put('admin_username', 'admin');
        return redirect()->route('admin.dashboard');
    }

    return back()->with('error', 'Username atau password salah.');
})->name('login.submit');

Route::post('/logout', function () {
    Session::forget('admin_logged_in');
    Session::forget('admin_username');
    return redirect()->route('login');
})->name('logout');

Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminController::class, 'saveSettings'])->name('admin.settings.save');
    Route::get('/admin/locations', [AdminController::class, 'locations'])->name('admin.locations');
    Route::get('/admin/locations/create', [AdminController::class, 'createLocation'])->name('admin.locations.create');
    Route::post('/admin/locations', [AdminController::class, 'storeLocation'])->name('admin.locations.store');
    Route::get('/admin/locations/{location}/edit', [AdminController::class, 'editLocation'])->name('admin.locations.edit');
    Route::put('/admin/locations/{location}', [AdminController::class, 'updateLocation'])->name('admin.locations.update');
    Route::delete('/admin/locations/{location}', [AdminController::class, 'destroyLocation'])->name('admin.locations.destroy');
    Route::get('/admin/employees', [AdminController::class, 'employees'])->name('admin.employees');
    Route::get('/admin/employees/create', [AdminController::class, 'createEmployee'])->name('admin.employees.create');
    Route::get('/admin/employees/{employee}/history', [AdminController::class, 'employeeHistory'])->name('admin.employees.history');
    Route::get('/admin/employees/{employee}/history/export', [AdminController::class, 'exportEmployeeHistory'])->name('admin.employees.history.export');
    Route::get('/admin/employees/{employee}/edit', [AdminController::class, 'editEmployee'])->name('admin.employees.edit');
    Route::get('/admin/employees/{employee}', [AdminController::class, 'showEmployee'])->name('admin.employees.show');
    Route::post('/admin/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
    Route::put('/admin/employees/{employee}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
    Route::delete('/admin/employees/{employee}', [AdminController::class, 'destroyEmployee'])->name('admin.employees.destroy');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/reports/export', [AdminController::class, 'exportReports'])->name('admin.reports.export');
    Route::get('/admin/attendance/{presensi}', [AdminController::class, 'attendanceDetail'])->name('admin.attendance.detail');
});

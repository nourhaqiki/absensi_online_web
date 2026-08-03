<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Presensi;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $settings = Setting::firstOrCreate([], [
            'app_name' => 'Absensi Online',
            'company_name' => 'PT Contoh',
            'timezone' => 'Asia/Jakarta',
            'attendance_required_photo' => true,
            'attendance_required_location' => true,
        ]);

        $locations = Location::latest()->take(5)->get();
        $employees = User::whereNotNull('nip')->latest()->take(5)->get();
        $attendanceLogs = Presensi::with(['user', 'location'])->latest()->take(8)->get();

        return view('admin.dashboard', compact('settings', 'locations', 'employees', 'attendanceLogs'));
    }

    public function settings()
    {
        $settings = Setting::firstOrCreate([], [
            'app_name' => 'Absensi Online',
            'company_name' => 'PT Contoh',
            'timezone' => 'Asia/Jakarta',
            'attendance_required_photo' => true,
            'attendance_required_location' => true,
        ]);

        return view('admin.settings', compact('settings'));
    }

    public function saveSettings(Request $request)
    {
        $settings = Setting::firstOrCreate([], [
            'app_name' => 'Absensi Online',
            'company_name' => 'PT Contoh',
            'timezone' => 'Asia/Jakarta',
            'attendance_required_photo' => true,
            'attendance_required_location' => true,
        ]);

        $settings->update($request->only(['app_name', 'company_name', 'timezone', 'attendance_required_photo', 'attendance_required_location']));

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function locations()
    {
        $locations = Location::latest()->get();
        return view('admin.locations', compact('locations'));
    }

    public function createLocation()
    {
        return view('admin.locations.create');
    }

    public function storeLocation(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius_meter' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);
        $data['radius_meter'] = $request->input('radius_meter', 100);

        Location::create($data);

        return redirect()->route('admin.locations')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function editLocation(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function updateLocation(Request $request, Location $location)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius_meter' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);
        $data['radius_meter'] = $request->input('radius_meter', 100);

        $location->update($data);

        return redirect()->route('admin.locations')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroyLocation(Location $location)
    {
        $location->delete();

        return redirect()->route('admin.locations')->with('success', 'Lokasi berhasil dihapus.');
    }

    public function employees()
    {
        $today = Carbon::today();
        $employees = User::whereNotNull('nip')
            ->with(['attendanceLogs' => function ($query) use ($today) {
                $query->whereDate('tanggal', $today)->orderBy('jam_masuk', 'desc');
            }])
            ->latest()
            ->get();

        return view('admin.employees', compact('employees'));
    }

    public function createEmployee()
    {
        return view('admin.employees_create');
    }

    public function showEmployee(User $employee)
    {
        return view('admin.employees_show', compact('employee'));
    }

    public function editEmployee(User $employee)
    {
        return view('admin.employees_edit', compact('employee'));
    }

    public function employeeHistory(User $employee)
    {
        $attendanceLogs = Presensi::with(['location', 'user'])
            ->where('user_id', $employee->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.employees_history', compact('employee', 'attendanceLogs'));
    }

    public function attendanceDetail(Presensi $presensi)
    {
        $presensi->load(['user', 'location']);

        return view('admin.attendance_detail', compact('presensi'));
    }

    public function exportEmployeeHistory(User $employee)
    {
        $filename = 'history-pegawai-' . strtolower(str_replace(' ', '-', $employee->name)) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($employee) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'NIP', 'Jabatan', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Lokasi', 'Status', 'Catatan']);

            foreach ($employee->attendanceLogs()->with('location')->orderBy('tanggal', 'desc')->get() as $log) {
                fputcsv($handle, [
                    $employee->name,
                    $employee->nip,
                    $employee->position,
                    $log->tanggal,
                    $log->jam_masuk,
                    $log->jam_pulang,
                    $log->location?->name,
                    $log->status,
                    $log->notes,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportReports()
    {
        $filename = 'laporan-absensi.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['User ID', 'Nama', 'NIP', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Lokasi', 'Status', 'Catatan']);

            Presensi::with(['user', 'location'])->orderBy('tanggal', 'desc')->get()->each(function ($log) use ($handle) {
                fputcsv($handle, [
                    $log->user?->id,
                    $log->user?->name,
                    $log->user?->nip,
                    $log->tanggal,
                    $log->jam_masuk,
                    $log->jam_pulang,
                    $log->location?->name,
                    $log->status,
                    $log->notes,
                ]);
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function storeEmployee(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:users,nip',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:6',
            'active' => 'nullable|boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['active'] = $request->boolean('active');

        foreach (['nip', 'email', 'position', 'phone'] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        } else {
            $data['password'] = Hash::make(Str::random(16));
        }

        $employee = User::create($data);

        return redirect()->route('admin.employees')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function updateEmployee(Request $request, User $employee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:users,nip,' . $employee->id,
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:6',
            'active' => 'nullable|boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['active'] = $request->boolean('active');

        foreach (['nip', 'email', 'position', 'phone'] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        } else {
            unset($data['password']);
        }

        $employee->update($data);

        return redirect()->route('admin.employees')->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroyEmployee(User $employee)
    {
        $employee->delete();

        return redirect()->route('admin.employees')->with('success', 'Pegawai berhasil dihapus.');
    }

    public function reports()
    {
        $attendanceLogs = Presensi::with(['user', 'location'])->latest()->get();
        return view('admin.reports', compact('attendanceLogs'));
    }
}

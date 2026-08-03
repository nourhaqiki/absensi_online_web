<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Presensi;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeedSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate([], [
            'app_name' => 'Absensi Online',
            'company_name' => 'PT Contoh',
            'timezone' => 'Asia/Jakarta',
            'attendance_required_photo' => true,
            'attendance_required_location' => true,
        ]);

        Location::firstOrCreate(['name' => 'Kantor Pusat'], [
            'address' => 'Jl. Merdeka No. 1',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'radius_meter' => 100,
            'active' => true,
        ]);

        $employee = User::firstOrCreate(['nip' => '001'], [
            'name' => 'Budi Santoso',
            'position' => 'Operator',
            'phone' => '08123456789',
            'email' => 'budi@example.com',
            'active' => true,
            'password' => bcrypt('password'),
        ]);

        Presensi::create([
            'user_id' => $employee->id,
            'location_id' => Location::first()->id,
            'tanggal' => now()->toDateString(),
            'jam_masuk' => now()->format('H:i:s'),
            'foto_masuk' => null,
            'status' => 'present',
            'notes' => 'Data awal',
        ]);
    }
}

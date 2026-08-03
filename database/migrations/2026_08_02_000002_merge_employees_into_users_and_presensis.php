<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'position')) {
                    $table->string('position')->nullable()->after('nip');
                }
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('position');
                }
                if (!Schema::hasColumn('users', 'active')) {
                    $table->boolean('active')->default(true)->after('phone');
                }
                if (!Schema::hasColumn('users', 'photo')) {
                    $table->string('photo')->nullable()->after('active');
                }
            });
        }

        $employeeMap = [];

        if (Schema::hasTable('employees')) {
            $employees = DB::table('employees')->get();

            foreach ($employees as $employee) {
                $user = null;

                if (!empty($employee->email)) {
                    $user = DB::table('users')->where('email', $employee->email)->first();
                }

                if (!$user && !empty($employee->nip)) {
                    $user = DB::table('users')->where('nip', $employee->nip)->first();
                }

                $data = [
                    'name' => $employee->name,
                    'nip' => $employee->nip,
                    'position' => $employee->position,
                    'phone' => $employee->phone,
                    'email' => $employee->email,
                    'photo' => $employee->photo,
                    'active' => $employee->active,
                    'updated_at' => Carbon::now(),
                ];

                if ($user) {
                    DB::table('users')->where('id', $user->id)->update($data);
                } else {
                    $data['password'] = Hash::make(Str::random(16));
                    $data['remember_token'] = Str::random(10);
                    $data['created_at'] = Carbon::now();
                    DB::table('users')->insert($data);
                }

                $employeeMap[$employee->id] = $employee;
            }

            Schema::dropIfExists('employees');
        }

        if (Schema::hasTable('presensis')) {
            Schema::table('presensis', function (Blueprint $table) {
                if (!Schema::hasColumn('presensis', 'location_id')) {
                    $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
                }
                if (!Schema::hasColumn('presensis', 'status')) {
                    $table->string('status')->nullable()->after('foto_pulang');
                }
                if (!Schema::hasColumn('presensis', 'notes')) {
                    $table->text('notes')->nullable()->after('status');
                }
            });
        }

        if (Schema::hasTable('attendance_logs')) {
            $attendanceLogs = DB::table('attendance_logs')->get();

            foreach ($attendanceLogs as $log) {
                $employee = $employeeMap[$log->employee_id] ?? null;
                if (!$employee) {
                    continue;
                }

                $user = null;
                if (!empty($employee->email)) {
                    $user = DB::table('users')->where('email', $employee->email)->first();
                }
                if (!$user && !empty($employee->nip)) {
                    $user = DB::table('users')->where('nip', $employee->nip)->first();
                }

                if (!$user) {
                    continue;
                }

                $attendanceDate = Carbon::parse($log->attendance_time)->toDateString();
                $attendanceTime = Carbon::parse($log->attendance_time)->format('H:i:s');

                $presensi = DB::table('presensis')
                    ->where('user_id', $user->id)
                    ->where('tanggal', $attendanceDate)
                    ->first();

                $data = [
                    'user_id' => $user->id,
                    'tanggal' => $attendanceDate,
                    'location_id' => $log->location_id,
                    'status' => $log->status,
                    'notes' => $log->notes,
                    'updated_at' => Carbon::now(),
                ];

                if ($log->type === 'check_in') {
                    $data['jam_masuk'] = $attendanceTime;
                    $data['foto_masuk'] = $log->photo_path;
                } elseif ($log->type === 'check_out') {
                    $data['jam_pulang'] = $attendanceTime;
                    $data['foto_pulang'] = $log->photo_path;
                }

                if ($presensi) {
                    DB::table('presensis')->where('id', $presensi->id)->update(array_filter($data, fn($value) => $value !== null));
                } else {
                    $data['jam_masuk'] = $data['jam_masuk'] ?? null;
                    $data['jam_pulang'] = $data['jam_pulang'] ?? null;
                    $data['foto_masuk'] = $data['foto_masuk'] ?? null;
                    $data['foto_pulang'] = $data['foto_pulang'] ?? null;
                    $data['lat_masuk'] = null;
                    $data['long_masuk'] = null;
                    $data['lat_pulang'] = null;
                    $data['long_pulang'] = null;
                    $data['created_at'] = Carbon::now();
                    DB::table('presensis')->insert($data);
                }
            }

            Schema::dropIfExists('attendance_logs');
        }
    }

    public function down(): void
    {
        // Rolling back data migration is not supported.
    }
};

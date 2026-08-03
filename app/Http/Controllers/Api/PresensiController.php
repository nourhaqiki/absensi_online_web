<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresensiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto' => 'required',
            'tipe' => 'required|in:masuk,pulang,datang',
        ]);

        $user = $request->user();
        $tipeInput = $request->input('tipe');
        $tipe = ($tipeInput === 'datang') ? 'masuk' : $tipeInput;
        $today = Carbon::now()->toDateString();

        $presensi = Presensi::where('user_id', $user->id)->where('tanggal', $today)->first();

        if ($tipe === 'masuk') {
            if ($presensi && $presensi->jam_masuk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah melakukan absen datang hari ini',
                    'data' => null,
                ], 409);
            }

            $file = $this->resolvePhotoFile($request, 'foto');
            $path = $this->storePresensiPhoto($file, $user->id, 'masuk');

            if (!$presensi) {
                $presensi = Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $today,
                    'jam_masuk' => Carbon::now()->format('H:i:s'),
                    'foto_masuk' => $path,
                    'lat_masuk' => $request->input('latitude'),
                    'long_masuk' => $request->input('longitude'),
                ]);
            } else {
                $presensi->update([
                    'jam_masuk' => Carbon::now()->format('H:i:s'),
                    'foto_masuk' => $path,
                    'lat_masuk' => $request->input('latitude'),
                    'long_masuk' => $request->input('longitude'),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Absen datang berhasil disimpan.',
                'data' => [
                    'id' => $presensi->id,
                    'tanggal' => $presensi->tanggal,
                    'jam_masuk' => $presensi->jam_masuk,
                    'foto_masuk_url' => Storage::disk('public')->url($presensi->foto_masuk),
                ],
            ], 201);
        }

        // tipe === pulang
        if (!$presensi || !$presensi->jam_masuk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda belum melakukan absen datang hari ini',
                'data' => null,
            ], 422);
        }

        if ($presensi->jam_pulang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah melakukan absen pulang hari ini',
                'data' => null,
            ], 409);
        }

        $file = $this->resolvePhotoFile($request, 'foto');
        $path = $this->storePresensiPhoto($file, $user->id, 'pulang');

        $presensi->update([
            'jam_pulang' => Carbon::now()->format('H:i:s'),
            'foto_pulang' => $path,
            'lat_pulang' => $request->input('latitude'),
            'long_pulang' => $request->input('longitude'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen pulang berhasil disimpan.',
            'data' => [
                'id' => $presensi->id,
                'tanggal' => $presensi->tanggal,
                'jam_pulang' => $presensi->jam_pulang,
                'foto_pulang_url' => Storage::disk('public')->url($presensi->foto_pulang),
            ],
        ], 200);
    }

    private function resolvePhotoFile(Request $request, string $field)
    {
        if ($request->hasFile($field)) {
            return $request->file($field);
        }

        $photoValue = $request->input($field);
        if (!is_string($photoValue) || trim($photoValue) === '') {
            abort(422, 'Foto tidak valid.');
        }

        if (preg_match('/^data:image\/([a-zA-Z]+);base64,/', $photoValue, $matches)) {
            $photoValue = substr($photoValue, strpos($photoValue, ',') + 1);
            $extension = strtolower($matches[1]);
        } else {
            $extension = 'jpg';
        }

        $decoded = base64_decode($photoValue, true);
        if ($decoded === false) {
            abort(422, 'Foto tidak valid.');
        }

        $tempPath = sys_get_temp_dir() . '/' . uniqid('absen_') . '.' . $extension;
        file_put_contents($tempPath, $decoded);

        return new \Illuminate\Http\UploadedFile($tempPath, basename($tempPath), 'image/' . $extension, null, true);
    }

    private function storePresensiPhoto(\Illuminate\Http\UploadedFile $file, int $userId, string $type): string
    {
        $ext = $file->getClientOriginalExtension();
        $filename = $type . '_' . $userId . '_' . time() . '.' . $ext;
        return $file->storeAs('absen_' . $type, $filename, 'public');
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $presensiList = Presensi::where('user_id', $user->id)
            ->orderByDesc('tanggal')
            ->get()
            ->map(function (Presensi $p) {
                return [
                    'id' => $p->id,
                    'tanggal' => $p->tanggal->toDateString(),
                    'jam_masuk' => $p->jam_masuk,
                    'jam_pulang' => $p->jam_pulang,
                    'foto_masuk_url' => $p->foto_masuk_url,
                    'foto_pulang_url' => $p->foto_pulang_url,
                    'lat_masuk' => $p->lat_masuk,
                    'long_masuk' => $p->long_masuk,
                    'lat_pulang' => $p->lat_pulang,
                    'long_pulang' => $p->long_pulang,
                    'created_at' => $p->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat absensi berhasil diambil.',
            'data' => $presensiList,
        ]);
    }
}

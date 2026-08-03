<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email|exists:users,email',
            'nip' => 'nullable|string|exists:users,nip',
            'password' => 'required|string',
        ]);

        if (!$request->filled('email') && !$request->filled('nip')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau NIP harus diisi.',
                'data' => null,
            ], 422);
        }

        $user = $request->filled('email')
            ? User::where('email', $request->input('email'))->first()
            : User::where('nip', $request->input('nip'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email/NIP atau password salah.',
                'data' => null,
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nip' => $user->nip,
                    'position' => $user->position,
                    'phone' => $user->phone,
                    'photo_url' => $user->photo_url,
                    'active' => $user->active,
                ],
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.',
            'data' => null,
        ]);
    }
}

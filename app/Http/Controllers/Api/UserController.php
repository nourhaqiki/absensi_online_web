<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile retrieved.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nip' => $user->nip ?? null,
                'position' => $user->position ?? null,
                'phone' => $user->phone ?? null,
                'photo' => $user->photo_url,
                'active' => $user->active,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$user->id,
            'nip' => 'nullable|string|max:100|unique:users,nip,'.$user->id,
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        $payload = [];
        if ($request->filled('name')) $payload['name'] = $request->input('name');
        if ($request->filled('email')) $payload['email'] = $request->input('email');
        if ($request->filled('nip')) $payload['nip'] = $request->input('nip');
        if ($request->filled('position')) $payload['position'] = $request->input('position');
        if ($request->filled('phone')) $payload['phone'] = $request->input('phone');
        if ($request->filled('password')) $payload['password'] = Hash::make($request->input('password'));

        if (!empty($payload)) {
            $user->update($payload);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nip' => $user->nip ?? null,
                'position' => $user->position ?? null,
                'phone' => $user->phone ?? null,
                'photo' => $user->photo_url,
                'active' => $user->active,
            ],
        ]);
    }
}

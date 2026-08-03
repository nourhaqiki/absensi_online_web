<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateApiUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'apiuser@example.com';
        $nip = '999999';
        $password = 'secret';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'API User',
                'email' => $email,
                'nip' => $nip,
                'password' => bcrypt($password),
            ]
        );

        // create token and write to storage so the developer can read it
        $token = $user->createToken('android')->plainTextToken;

        $path = storage_path('app/api_token.txt');
        @file_put_contents($path, "EMAIL: $email\nNIP: $nip\nPASSWORD: $password\nTOKEN: $token\n");
    }
}

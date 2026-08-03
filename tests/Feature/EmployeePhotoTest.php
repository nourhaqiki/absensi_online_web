<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeePhotoTest extends TestCase
{
    use RefreshDatabase;
    public function test_employee_can_be_created_with_photo(): void
    {
        Storage::fake('public');

        $uniqueEmail = 'budi+' . uniqid() . '@example.com';

        $response = $this->withSession(['admin_logged_in' => true])
            ->post(route('admin.employees.store'), [
                'name' => 'Budi Santoso',
                'nip' => '123456',
                'position' => 'Staff',
                'phone' => '081234567890',
                'email' => $uniqueEmail,
                'active' => true,
                'password' => 'secret123',
                'photo' => UploadedFile::fake()->image('avatar.jpg'),
            ]);

        $response->assertRedirect(route('admin.employees'));

        $employee = User::latest()->first();
        $this->assertNotNull($employee);
        $this->assertNotNull($employee->photo);
        Storage::disk('public')->assertExists($employee->photo);
    }

    public function test_employee_edit_page_can_be_rendered(): void
    {
        $employee = User::create([
            'name' => 'Tono',
            'nip' => '654321',
            'position' => 'Operator',
            'phone' => '081122334455',
            'email' => 'tono+' . uniqid() . '@example.com',
            'active' => true,
            'password' => bcrypt('password'),
        ]);

        $response = $this->withSession(['admin_logged_in' => true])
            ->get(route('admin.employees.edit', $employee));

        $response->assertStatus(200);
        $response->assertSee($employee->name);
    }
}

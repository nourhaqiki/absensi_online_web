<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('Absensi Online');
            $table->string('company_name')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->boolean('attendance_required_photo')->default(true);
            $table->boolean('attendance_required_location')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

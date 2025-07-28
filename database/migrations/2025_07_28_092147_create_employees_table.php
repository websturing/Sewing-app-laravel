<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            // Kolom tambahan yang umum di absensi
            $table->string('employee_code')->unique(); // NIP / kode pegawai
            $table->string('position')->nullable();     // Jabatan
            $table->string('department')->nullable();   // Departemen
            $table->date('join_date')->nullable();      // Tanggal masuk
            $table->boolean('active')->default(true);   // Status aktif

            // Kolom fingerprint atau device ID (opsional)
            $table->string('device_id')->nullable();    // Untuk pairing dengan Android

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

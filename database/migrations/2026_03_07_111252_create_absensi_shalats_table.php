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
        Schema::create('absensi_shalats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('waktu_shalat', ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']);
            $table->enum('status', ['hadir', 'masbuk', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['santri_id', 'tanggal', 'waktu_shalat'], 'unik_absensi_shalat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_shalats');
    }
};

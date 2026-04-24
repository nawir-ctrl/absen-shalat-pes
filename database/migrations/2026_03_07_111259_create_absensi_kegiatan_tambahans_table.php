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
        Schema::create('absensi_kegiatan_tambahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('kegiatan_tambahan_id')->constrained('kegiatan_tambahans')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(
                ['santri_id', 'kegiatan_tambahan_id', 'tanggal'],
                'unik_absensi_kegiatan'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_kegiatan_tambahans');
    }
};
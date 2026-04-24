<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_pesantrens', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_statistik')->nullable();
            $table->string('nama_pesantren');
            $table->text('alamat')->nullable();
            $table->string('nama_pimpinan')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_pesantrens');
    }
};
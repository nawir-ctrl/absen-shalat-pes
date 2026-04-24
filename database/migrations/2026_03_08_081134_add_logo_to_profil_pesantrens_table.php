<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_pesantrens', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('nomor_hp');
        });
    }

    public function down(): void
    {
        Schema::table('profil_pesantrens', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
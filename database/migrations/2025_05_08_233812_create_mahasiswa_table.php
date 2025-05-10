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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->unsignedBigInteger('id_akun')->unique()->index();
            $table->unsignedBigInteger('id_prodi')->index();
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('telepon', 30)->unique();
            $table->dateTime('tanggal_lahir');
            $table->string('email', 100);
            $table->timestamps();

            $table->foreign('id_akun')->references('id_akun')->on('akun')->onDelete('cascade');
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};

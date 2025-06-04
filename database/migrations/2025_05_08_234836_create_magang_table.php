<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('magang', function (Blueprint $table) {
            $table->id('id_magang');
            $table->unsignedBigInteger('id_mahasiswa')->index();
            $table->unsignedBigInteger('id_dosen')->nullable()->index();
            $table->unsignedBigInteger('id_periode')->index();
            $table->text('alasan_penolakan')->nullable();
            $table->enum('status', ['proses', 'diterima', 'ditolak', 'lulus'])->default('proses');
            $table->dateTime('tanggal_pengajuan');
            $table->timestamps();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
            $table->foreign('id_periode')->references('id_periode')->on('periode_magang')->onDelete('cascade');

            $table->unique(['id_mahasiswa', 'id_periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magang');
    }
};

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
        Schema::create('lowongan_magang', function (Blueprint $table) {
            $table->id('id_lowongan');
            $table->unsignedBigInteger('id_perusahaan')->index();
            $table->unsignedBigInteger('id_bidang')->index();
            $table->string('nama', 100);
            $table->text('persyaratan');
            $table->text('deskripsi');
            $table->timestamps();

            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('perusahaan')->onDelete('cascade');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang')->onDelete('cascade');

            $table->unique(['id_perusahaan', 'id_bidang', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan_magang');
    }
};

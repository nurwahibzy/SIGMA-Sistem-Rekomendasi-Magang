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
        Schema::create('periode_magang', function (Blueprint $table) {
            $table->id('id_periode');
            $table->unsignedBigInteger('id_lowongan')->index();
            $table->string('nama', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();

            $table->foreign('id_lowongan')->references('id_lowongan')->on('lowongan_magang')->onDelete('cascade');

            $table->unique(['id_lowongan', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_magang');
    }
};

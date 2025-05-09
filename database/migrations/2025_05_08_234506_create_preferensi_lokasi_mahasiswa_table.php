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
        Schema::create('preferensi_lokasi_mahasiswa', function (Blueprint $table) {
            $table->id('id_preferensi_lokasi');
            $table->unsignedBigInteger('id_mahasiswa')->unique()->index();
            $table->string('provinsi', 30);
            $table->string('daerah', 30);
            $table->string('latitude', 15);
            $table->string('longitude', 15);
            $table->timestamps();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferensi_lokasi_mahasiswa');
    }
};

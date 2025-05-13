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
        Schema::create('keahlian_mahasiswa', function (Blueprint $table) {
            $table->id('id_keahlian_mahasiswa');
            $table->unsignedBigInteger('id_mahasiswa')->index();
            $table->unsignedBigInteger('id_bidang')->index();
            $table->integer('prioritas')->nullable();
            $table->text('keahlian');
            $table->timestamps();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang')->onDelete('cascade');

            $table->unique(['id_mahasiswa', 'id_bidang']);
            $table->unique(['id_mahasiswa', 'prioritas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keahlian_mahasiswa');
    }
};

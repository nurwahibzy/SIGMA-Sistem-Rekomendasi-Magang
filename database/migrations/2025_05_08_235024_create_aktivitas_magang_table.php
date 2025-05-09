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
        Schema::create('aktivitas_magang', function (Blueprint $table) {
            $table->id('id_aktivitas');
            $table->unsignedBigInteger('id_magang')->index();
            $table->date('tanggal');
            $table->text('keterangan');
            $table->text('foto_path');
            $table->timestamps();

            $table->foreign('id_magang')->references('id_magang')->on('magang')->onDelete('cascade');

            $table->unique(['id_magang', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_magang');
    }
};

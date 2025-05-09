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
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id('id_perusahaan');
            $table->unsignedBigInteger('id_jenis')->index();
            $table->string('nama', 100);
            $table->string('telepon', 30)->unique();
            $table->text('deskripsi');
            $table->text('foto_path');
            $table->string('provinsi', 30);
            $table->string('daerah', 30);
            $table->string('latitude', 15);
            $table->string('longitude', 15);
            $table->timestamps();

            $table->foreign('id_jenis')->references('id_jenis')->on('jenis_perusahaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};

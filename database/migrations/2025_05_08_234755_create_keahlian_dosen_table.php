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
        Schema::create('keahlian_dosen', function (Blueprint $table) {
            $table->id('id_keahlian_dosen');
            $table->unsignedBigInteger('id_dosen')->index();
            $table->unsignedBigInteger('id_bidang')->index();
            $table->text('keahlian');
            $table->timestamps();

            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang')->onDelete('cascade');

            $table->unique(['id_dosen', 'id_bidang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keahlian_dosen');
    }
};

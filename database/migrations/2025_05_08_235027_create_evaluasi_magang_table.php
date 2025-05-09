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
        Schema::create('evaluasi_magang', function (Blueprint $table) {
            $table->id('id_evaluasi');
            $table->unsignedBigInteger('id_magang')->index();
            $table->text('feedback');
            $table->timestamps();

            $table->foreign('id_magang')->references('id_magang')->on('magang')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_magang');
    }
};

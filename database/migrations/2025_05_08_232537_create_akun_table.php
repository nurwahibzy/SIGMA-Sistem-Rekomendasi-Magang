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
        Schema::create('akun', function (Blueprint $table) {
            $table->id('id_akun');
            $table->unsignedBigInteger('id_level')->index();
            $table->string('username', 20)->unique();
            $table->string('password');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('foto_path')->nullable();
            $table->timestamps();

            $table->foreign('id_level')->references('id_level')->on('level')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};

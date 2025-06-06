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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jadwal');
            $table->date('tanggal');
            $table->enum('status_kehadiran', ['Hadir', 'Sakit', 'Alpa'])->default('Hadir');
            $table->longText('pokok_pembahasan')->nullable();
            $table->string('koordinat')->nullable();
            $table->timestamps();

            $table->foreign('id_jadwal')->references('id')->on('jadwal')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};

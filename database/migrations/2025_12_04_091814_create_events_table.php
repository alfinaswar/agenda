<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->enum('Jenis', ['Internal', 'Eksternal'])->nullable();
            $table->string('NamaEvent');
            $table->string('Deskripsi');
            $table->text('Keterangan')->nullable();
            $table->date('TanggalMulai');
            $table->date('TanggalSelesai')->nullable();
            $table->string('Lokasi')->nullable();
            $table->string('UserCreate')->nullable();
            $table->string('UserUpdate')->nullable();
            $table->string('UserDelete')->nullable();
            $table->softDeletes();
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

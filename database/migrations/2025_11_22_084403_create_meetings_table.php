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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('Ruangan')->nullable();
            $table->string('JudulMeeting');
            $table->text('DeskripsiMeeting')->nullable();
            $table->date('Tanggal');
            $table->time('JamMulai');
            $table->time('JamSelesai');
            $table->integer('DurasiMenit')->nullable(); // opsional dihitung otomatis
            $table->string('TautanMeeting')->nullable();
            $table->string('LampiranAgenda')->nullable(); // bisa upload file
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
        Schema::dropIfExists('meetings');
    }
};

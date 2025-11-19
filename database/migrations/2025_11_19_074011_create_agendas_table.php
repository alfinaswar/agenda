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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            // Informasi Agenda
            $table->string('JudulAgenda');                          // Judul Agenda
            $table->text('DeskripsiAgenda')->nullable();            // Deskripsi Agenda

            // Waktu Agenda
            $table->date('TanggalMulai');                           // Tanggal Mulai
            $table->date('TanggalSelesai')->nullable();             // Tanggal Selesai
            $table->time('JamMulai')->nullable();                   // Jam Mulai
            $table->time('JamSelesai')->nullable();                 // Jam Selesai

            // Detail Lokasi
            $table->string('LokasiAgenda')->nullable();             // Lokasi Agenda
            $table->string('TautanRapat')->nullable();              // Tautan Rapat (Zoom / GMeet)
            // Kategori Dan Status
            $table->string('KategoriAgenda')->nullable();           // Kategori Agenda
            $table->enum('StatusAgenda', ['Draft', 'Pending', 'Disetujui', 'Selesai', 'Dibatalkan'])
                ->default('Pending');

            // Lampiran
            $table->string('LampiranAgenda')->nullable();
            $table->string('UserCreate')->nullable();
            $table->string('UserUpdate')->nullable();
            $table->string('UserDelete')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};

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
        Schema::create('peserta_events', function (Blueprint $table) {
            $table->id();
            $table->string('EventId')->nullable();
            $table->string('Nik')->nullable();
            $table->string('NamaPeserta')->nullable();
            $table->string('AsalUnit')->nullable();
            $table->enum('Gender', ['L', 'P'])->nullable();
            $table->enum('Hadir', ['Y', 'N'])->nullable();
            $table->longText('TandaTangan')->nullable();
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
        Schema::dropIfExists('peserta_events');
    }
};

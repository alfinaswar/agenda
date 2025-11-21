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
        Schema::create('master_ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('NamaRuangan');
            $table->integer('Kapasitas')->nullable();
            $table->string('Lokasi')->nullable();
            $table->string('Fasilitas')->nullable();
            $table->string('StatusRuangan')->default('Aktif');
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
        Schema::dropIfExists('master_ruangans');
    }
};

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
        Schema::create('pengaturan_homes', function (Blueprint $table) {
            $table->id();
            $table->enum('ShowAgenda', ['Y', 'N'])->nullable();
            $table->enum('ShowBooking', ['Y', 'N'])->nullable();
            $table->enum('ShowEvent', ['Y', 'N'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_homes');
    }
};

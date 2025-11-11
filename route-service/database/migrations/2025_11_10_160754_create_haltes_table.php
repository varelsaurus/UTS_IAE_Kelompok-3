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
        Schema::create('halte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rute')->cascadeOnDelete();
            $table->string('nama_halte');
            $table->unsignedInteger('urutan');
            $table->timestamps();
            $table->unique(['rute_id','urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halte');
        Schema::dropIfExists('haltes');
    }
};

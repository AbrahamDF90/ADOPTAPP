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
    Schema::create('adopciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('publicacion_id')->constrained('publicaciones')->cascadeOnDelete();
        $table->foreignId('adoptante_id')->constrained('adoptantes')->cascadeOnDelete();
        $table->string('estado_adopcion', 20)->default('activa');
        $table->string('motivo_cancelacion')->nullable();
        $table->string('estado_mascota', 20)->default('adoptada');
        $table->date('fecha_adopcion');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adopciones');
    }
};

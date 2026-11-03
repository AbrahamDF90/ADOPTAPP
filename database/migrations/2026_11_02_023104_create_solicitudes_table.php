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
    Schema::create('solicitudes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('publicacion_id')->constrained('publicaciones')->cascadeOnDelete();
        $table->foreignId('adoptante_id')->constrained('adoptantes')->cascadeOnDelete();
        $table->string('estado', 30)->default('pendiente');
        $table->dateTime('fecha_entrevista')->nullable();
        $table->string('motivo_cierre')->nullable();
        $table->timestamps();
        $table->softDeletes();
        $table->unique(['publicacion_id', 'adoptante_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};

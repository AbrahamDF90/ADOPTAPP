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
    Schema::create('publicaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('refugio_id')->constrained('refugios')->cascadeOnDelete();
        $table->string('foto');
        $table->string('especie');
        $table->string('sexo');
        $table->string('edad_aproximada');
        $table->string('estado')->default('activa'); 
        $table->text('motivo_baja')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
    }
};

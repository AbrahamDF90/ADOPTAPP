<?php

namespace Database\Factories;

use App\Models\Adoptante;
use App\Models\Publicacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdopcionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'publicacion_id' => Publicacion::factory(),
            'adoptante_id' => Adoptante::factory(),
            'estado_adopcion' => 'activa',
            'estado_mascota' => 'adoptada',
            'fecha_adopcion' => now()->toDateString(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Adoptante;
use App\Models\Publicacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudFactory extends Factory
{
    public function definition(): array
    {
        return [
            'publicacion_id' => Publicacion::factory(),
            'adoptante_id' => Adoptante::factory(),
            'estado' => 'pendiente',
            'fecha_entrevista' => null,
            'motivo_cierre' => null,
        ];
    }
}

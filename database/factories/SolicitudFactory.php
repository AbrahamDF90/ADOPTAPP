<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudFactory extends Factory
{
    public function definition(): array
    {
        return [
            'estado' => 'pendiente',
            'fecha_entrevista' => null,
            'motivo_cierre' => null,
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdopcionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'estado_adopcion' => 'activa',
            'estado_mascota' => 'adoptada',
            'fecha_adopcion' => now(), // <-- Agregamos la fecha actual
        ];
    }
}

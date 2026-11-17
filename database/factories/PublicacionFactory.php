<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PublicacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'especie' => $this->faker->randomElement(['Perro', 'Gato']),
            'sexo' => $this->faker->randomElement(['Macho', 'Hembra']),
            'edad_aproximada' => $this->faker->numberBetween(1, 10),
            'estado' => 'activa',
            'foto' => 'publicaciones/test.jpg',
        ];
    }
}

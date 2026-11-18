<?php

namespace Database\Factories;

use App\Models\Refugio;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'refugio_id' => Refugio::factory(),
            'especie' => $this->faker->randomElement(['Perro', 'Gato']),
            'sexo' => $this->faker->randomElement(['Macho', 'Hembra']),
            'edad_aproximada' => $this->faker->numberBetween(1, 10),
            'estado' => 'activa',
            'foto' => 'publicaciones/test.jpg',
        ];
    }
}

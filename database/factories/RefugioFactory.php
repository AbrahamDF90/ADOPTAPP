<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RefugioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company(),
            'ubicacion' => $this->faker->city(),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
            'activo' => true,
        ];
    }
}

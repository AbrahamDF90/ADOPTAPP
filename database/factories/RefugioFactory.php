<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefugioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'refugio']),
            'nombre' => $this->faker->company(),
            'ubicacion' => $this->faker->city(),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
            'activo' => true,
        ];
    }
}

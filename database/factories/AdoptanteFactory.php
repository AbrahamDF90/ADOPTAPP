<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdoptanteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'adoptante']),
            'dni' => $this->faker->unique()->numerify('########'),
            'alias' => $this->faker->userName(),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
            'foto' => 'adoptantes/test.jpg',
        ];
    }
}

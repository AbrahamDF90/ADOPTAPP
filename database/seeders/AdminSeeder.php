<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador ADOPTAPP',
            'email' => 'admin@adoptapp.com',
            'password' => Hash::make('admin1234'), // La podés cambiar luego
            'role' => 'admin',
        ]);
    }
}
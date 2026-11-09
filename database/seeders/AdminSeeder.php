<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
{
    $password = Hash::make('admin1234');
    
    User::create(['name' => 'Admin Turno Mañana', 'email' => 'admin1@adoptapp.com', 'password' => $password, 'role' => 'admin']);
    User::create(['name' => 'Admin Turno Tarde', 'email' => 'admin2@adoptapp.com', 'password' => $password, 'role' => 'admin']);
    User::create(['name' => 'Admin Turno Noche', 'email' => 'admin3@adoptapp.com', 'password' => $password, 'role' => 'admin']);
}
}
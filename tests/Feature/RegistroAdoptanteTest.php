<?php

use App\Models\Adoptante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// Esta línea mágica le dice a Laravel que cree las tablas en la BD de prueba
uses(RefreshDatabase::class);

test('un adoptante puede registrarse correctamente con su foto', function () {
    Storage::fake('public');
    $foto = UploadedFile::fake()->image('perfil.jpg');

    $response = $this->post('/register', [
        'name' => 'Test Adoptante',
        'email' => 'test@adoptapp.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'dni' => '99887766',
        'telefono' => '123456789',
        'direccion' => 'Calle Falsa 123',
        'foto' => $foto,
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('users', [
        'email' => 'test@adoptapp.com',
        'role' => 'adoptante',
    ]);

    $this->assertDatabaseHas('adoptantes', [
        'dni' => '99887766',
        'direccion' => 'Calle Falsa 123',
    ]);

    $adoptante = Adoptante::where('dni', '99887766')->first();
    Storage::disk('public')->assertExists($adoptante->foto);
});
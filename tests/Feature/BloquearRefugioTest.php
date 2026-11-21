<?php

use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un admin puede bloquear un refugio y esto da de baja sus publicaciones', function () {
    // 1. Arrange
    $admin = User::factory()->create(['role' => 'admin']);

    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id, 'activo' => true]);

    $publicacion = Publicacion::factory()->create([
        'refugio_id' => $refugio->id,
        'estado' => 'activa',
    ]);

    // 2. Act
    $response = $this->actingAs($admin)->patch(route('admin.refugios.toggle', $refugio));

    // 3. Assert
    $response->assertRedirect();

    // Verificamos que el refugio se bloqueó
    $this->assertDatabaseHas('refugios', [
        'id' => $refugio->id,
        'activo' => false,
    ]);

    // Verificamos que la publicación se dio de baja
    $this->assertDatabaseHas('publicaciones', [
        'id' => $publicacion->id,
        'estado' => 'baja',
        'motivo_baja' => 'Refugio bloqueado por el administrador.',
    ]);
});

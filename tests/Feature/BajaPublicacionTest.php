<?php

use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un refugio puede dar de baja su propia publicacion', function () {
    // 1. Arrange
    $user = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $user->id]);
    $publicacion = Publicacion::factory()->create(['refugio_id' => $refugio->id]);

    // 2. Act
    $response = $this->actingAs($user)->delete(route('refugio.publicaciones.destroy', $publicacion), [
        'motivo_baja' => 'La mascota ya fue adoptada por otra vía.',
    ]);

    // 3. Assert
    $response->assertRedirect(route('refugio.dashboard'));

    // Verificamos que se guardó el motivo
    $this->assertDatabaseHas('publicaciones', [
        'id' => $publicacion->id,
        'estado' => 'baja',
        'motivo_baja' => 'La mascota ya fue adoptada por otra vía.',
    ]);

    // Verificamos que se aplicó el SoftDelete
    $this->assertSoftDeleted('publicaciones', ['id' => $publicacion->id]);
});

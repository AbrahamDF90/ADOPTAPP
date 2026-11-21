<?php

use App\Models\Adoptante;
use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un adoptante puede cancelar su solicitud pendiente', function () {
    // 1. Arrange
    $user = User::factory()->create(['role' => 'adoptante']);
    $adoptante = Adoptante::factory()->create(['user_id' => $user->id]);

    $refugioUser = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $refugioUser->id]);

    $publicacion = Publicacion::factory()->create([
        'refugio_id' => $refugio->id,
        'estado' => 'completa', // Simulamos que llegó al límite
    ]);

    $solicitud = Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado' => 'pendiente',
    ]);

    // 2. Act
    $response = $this->actingAs($user)->patch(route('adoptante.solicitudes.cancelar', $solicitud));

    // 3. Assert
    $response->assertRedirect();

    // Verificamos que la solicitud se canceló
    $this->assertDatabaseHas('solicitudes', [
        'id' => $solicitud->id,
        'estado' => 'cancelada',
        'motivo_cierre' => 'Cancelada por el adoptante.',
    ]);

    // Verificamos que la publicación volvió a estar activa
    $this->assertDatabaseHas('publicaciones', [
        'id' => $publicacion->id,
        'estado' => 'activa',
    ]);
});

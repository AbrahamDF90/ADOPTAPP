<?php

use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * HU-20: El refugio acepta una solicitud y el sistema cierra las demás,
 * cambia el estado de la publicación y crea el registro de adopción.
 *
 * Patrón testeado: Transaction Script (atomicidad de 3 tablas).
 */
it('permite al refugio aceptar una solicitud y cierra las demás', function () {
    // Arrange
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id]);

    $publicacion = Publicacion::factory()->create([
        'refugio_id' => $refugio->id,
        'estado' => 'completa',
    ]);

    $solicitudAceptar = Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'estado' => 'pendiente',
    ]);

    $otraSolicitud = Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'estado' => 'pendiente',
    ]);

    // Act
    $response = $this->actingAs($userRefugio)
        ->patch(route('refugio.solicitudes.aceptar', $solicitudAceptar));

    // Assert
    $response->assertRedirect(route('refugio.solicitudes.index'));

    expect($solicitudAceptar->fresh()->estado)->toBe('aceptada');
    expect($otraSolicitud->fresh()->estado)->toBe('cerrada');
    expect($publicacion->fresh()->estado)->toBe('adoptada');

    $this->assertDatabaseHas('adopciones', [
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $solicitudAceptar->adoptante_id,
        'estado_adopcion' => 'activa',
        'estado_mascota' => 'adoptada',
    ]);
});

it('no permite aceptar una solicitud de otro refugio', function () {
    // Arrange
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    Refugio::factory()->create(['user_id' => $userRefugio->id]);

    $otroRefugio = Refugio::factory()->create();
    $publicacion = Publicacion::factory()->create(['refugio_id' => $otroRefugio->id]);
    $solicitud = Solicitud::factory()->create(['publicacion_id' => $publicacion->id]);

    // Act
    $response = $this->actingAs($userRefugio)
        ->patch(route('refugio.solicitudes.aceptar', $solicitud));

    // Assert
    $response->assertForbidden();
    expect($solicitud->fresh()->estado)->not->toBe('aceptada');
});

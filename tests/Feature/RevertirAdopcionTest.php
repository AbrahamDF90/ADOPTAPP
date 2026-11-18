<?php

use App\Models\Adopcion;
use App\Models\Adoptante;
use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * HU-21: El refugio revierte una adopción activa y el sistema devuelve
 * la publicación a 'completa' y la solicitud a 'pendiente'.
 *
 * Patrón testeado: Transaction Script (atomicidad de 3 tablas).
 */
it('permite al refugio revertir una adopción activa', function () {
    // Arrange
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id]);

    $publicacion = Publicacion::factory()->create([
        'refugio_id' => $refugio->id,
        'estado' => 'adoptada',
    ]);

    $adoptante = Adoptante::factory()->create();

    $solicitud = Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado' => 'aceptada',
    ]);

    $adopcion = Adopcion::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado_adopcion' => 'activa',
        'estado_mascota' => 'adoptada',
    ]);

    // Act
    $response = $this->actingAs($userRefugio)
        ->patch(route('refugio.solicitudes.revertir', $solicitud));

    // Assert
    $response->assertRedirect(route('refugio.solicitudes.index'));

    expect($adopcion->fresh()->estado_adopcion)->toBe('cancelada');
    expect($adopcion->fresh()->motivo_cancelacion)->not->toBeNull();
    expect($publicacion->fresh()->estado)->toBe('completa');
    expect($solicitud->fresh()->estado)->toBe('pendiente');
});

it('no permite revertir una adopción de otro refugio', function () {
    // Arrange
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    Refugio::factory()->create(['user_id' => $userRefugio->id]);

    $otroRefugio = Refugio::factory()->create();
    $publicacion = Publicacion::factory()->create([
        'refugio_id' => $otroRefugio->id,
        'estado' => 'adoptada',
    ]);

    $adoptante = Adoptante::factory()->create();

    $solicitud = Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado' => 'aceptada',
    ]);

    $adopcion = Adopcion::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado_adopcion' => 'activa',
    ]);

    // Act
    $response = $this->actingAs($userRefugio)
        ->patch(route('refugio.solicitudes.revertir', $solicitud));

    // Assert
    $response->assertForbidden();
    expect($adopcion->fresh()->estado_adopcion)->toBe('activa');
});

it('no permite revertir una solicitud sin adopción activa', function () {
    // Arrange
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id]);

    $publicacion = Publicacion::factory()->create([
        'refugio_id' => $refugio->id,
        'estado' => 'completa',
    ]);

    $solicitud = Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'estado' => 'pendiente',
    ]);

    // Act (no hay adopción activa asociada)
    $response = $this->actingAs($userRefugio)
        ->patch(route('refugio.solicitudes.revertir', $solicitud));

    // Assert
    $response->assertRedirect(route('refugio.solicitudes.show', $solicitud));
    $response->assertSessionHas('error');
    expect($solicitud->fresh()->estado)->toBe('pendiente');
});

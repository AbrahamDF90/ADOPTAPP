<?php

use App\Models\Adopcion;
use App\Models\Adoptante;
use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un refugio puede actualizar el estado de su mascota adoptada', function () {
    // 1. Creamos el refugio dueño de la publicación
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id]);
    $publicacion = Publicacion::factory()->create(['refugio_id' => $refugio->id]);

    // 2. Creamos al adoptante (¡Esto faltaba!)
    $userAdoptante = User::factory()->create(['role' => 'adoptante']);
    $adoptante = Adoptante::factory()->create(['user_id' => $userAdoptante->id]);

    // 3. Creamos la adopción completa
    $adopcion = Adopcion::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id, // <-- AGREGADO
        'estado_mascota' => 'adoptada',
    ]);

    // 4. El refugio actualiza el estado
    $response = $this->actingAs($userRefugio)->patch(route('refugio.adopciones.estado', $adopcion), [
        'estado_mascota' => 'fallecida',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('adopciones', [
        'id' => $adopcion->id,
        'estado_mascota' => 'fallecida',
    ]);
});

test('un refugio NO puede actualizar el estado de una adopcion ajena', function () {
    // Refugio 1 (el que intenta modificar)
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    Refugio::factory()->create(['user_id' => $userRefugio->id]);

    // Refugio 2 (dueño de la adopción)
    $userRefugioAjeno = User::factory()->create(['role' => 'refugio']);
    $refugioAjeno = Refugio::factory()->create(['user_id' => $userRefugioAjeno->id]);
    $publicacionAjena = Publicacion::factory()->create(['refugio_id' => $refugioAjeno->id]);

    // Creamos al adoptante
    $userAdoptante = User::factory()->create(['role' => 'adoptante']);
    $adoptante = Adoptante::factory()->create(['user_id' => $userAdoptante->id]);

    // Creamos la adopción ajena completa
    $adopcionAjena = Adopcion::factory()->create([
        'publicacion_id' => $publicacionAjena->id,
        'adoptante_id' => $adoptante->id, // <-- AGREGADO
    ]);

    // El Refugio 1 intenta modificar la adopción del Refugio 2
    $response = $this->actingAs($userRefugio)->patch(route('refugio.adopciones.estado', $adopcionAjena), [
        'estado_mascota' => 'robada',
    ]);

    // Verificamos que el sistema lo bloquee (403)
    $response->assertForbidden();
});

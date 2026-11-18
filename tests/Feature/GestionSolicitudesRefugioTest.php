<?php

use App\Models\Adoptante;
use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un refugio ve solo las solicitudes de sus publicaciones', function () {
    // 1. Refugio dueño
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id]);

    // NOTA: Forzamos la especie para evitar "Flaky Tests" en el CI/CD.
    // Si usábamos Faker, a veces ambas publicaciones eran "Perro" y el test fallaba.

    $publicacion = Publicacion::factory()->create(['refugio_id' => $refugio->id, 'especie' => 'Perro']);

    // 2. Refugio ajeno
    $userRefugioAjeno = User::factory()->create(['role' => 'refugio']);
    $refugioAjeno = Refugio::factory()->create(['user_id' => $userRefugioAjeno->id]);
    $publicacionAjena = Publicacion::factory()->create(['refugio_id' => $refugioAjeno->id, 'especie' => 'Gato']);

    // 3. Adoptante (Nombre: Juan Perez, Alias: FirulaisLover)
    $userAdoptante = User::factory()->create(['role' => 'adoptante', 'name' => 'Juan Perez']);
    $adoptante = Adoptante::factory()->create([
        'user_id' => $userAdoptante->id,
        'alias' => 'FirulaisLover',
    ]);

    // 4. Creamos una solicitud para el refugio dueño y otra para el ajeno
    Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
    ]);

    Solicitud::factory()->create([
        'publicacion_id' => $publicacionAjena->id,
        'adoptante_id' => $adoptante->id,
    ]);

    // Actuamos como el refugio dueño entrando a la ruta
    $response = $this->actingAs($userRefugio)->get(route('refugio.solicitudes.index'));

    // Verificamos que cargue bien (200) y que vea al adoptante
    $response->assertOk();
    $response->assertSee('FirulaisLover');

    // Verificamos que NO vea la mascota del otro refugio
    $response->assertDontSee('Gato'); // Forzamos a que busque esta palabra
});

test('un refugio no puede ver solicitudes ajenas', function () {
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    Refugio::factory()->create(['user_id' => $userRefugio->id]);

    $userRefugioAjeno = User::factory()->create(['role' => 'refugio']);
    $refugioAjeno = Refugio::factory()->create(['user_id' => $userRefugioAjeno->id]);
    $publicacionAjena = Publicacion::factory()->create(['refugio_id' => $refugioAjeno->id]);

    $userAdoptante = User::factory()->create(['role' => 'adoptante']);
    $adoptante = Adoptante::factory()->create(['user_id' => $userAdoptante->id]);

    $solicitudAjena = Solicitud::factory()->create([
        'publicacion_id' => $publicacionAjena->id,
        'adoptante_id' => $adoptante->id,
    ]);

    $response = $this->actingAs($userRefugio)->get(route('refugio.solicitudes.show', $solicitudAjena));
    $response->assertForbidden();
});

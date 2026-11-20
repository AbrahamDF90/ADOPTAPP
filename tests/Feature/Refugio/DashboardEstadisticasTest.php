<?php

use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * HU-28: Dashboard de estadísticas para Refugio.
 * Patrón: Feature test con RefreshDatabase (requerido por CI, HU-31).
 */
it('muestra solo las métricas del refugio autenticado', function () {
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id]);

    // Publicación del refugio autenticado
    Publicacion::factory()->create([
        'refugio_id' => $refugio->id,
        'estado' => 'activa',
    ]);

    // Publicación de OTRO refugio (no debe contarse)
    $otroRefugio = Refugio::factory()->create();
    Publicacion::factory()->create([
        'refugio_id' => $otroRefugio->id,
        'estado' => 'activa',
    ]);

    $response = $this->actingAs($userRefugio)->get(route('refugio.dashboard'));

    $response->assertOk();
    $response->assertSee('Panel del Refugio');
    $response->assertViewHas('metricas', function ($metricas) {
        return $metricas['total_publicaciones'] === 1
            && $metricas['publicaciones_activas'] === 1;
    });
});

it('bloquea el acceso al dashboard refugio a un adoptante', function () {
    $adoptante = User::factory()->create(['role' => 'adoptante']);

    $this->actingAs($adoptante)
        ->get(route('refugio.dashboard'))
        ->assertForbidden();
});

it('bloquea el acceso al dashboard refugio a un admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('refugio.dashboard'))
        ->assertForbidden();
});

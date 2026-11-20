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
 * HU-28: Dashboard de estadísticas para Admin.
 * Patrón: Feature test con RefreshDatabase (requerido por CI, HU-31).
 */
it('muestra métricas globales al admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $adoptante = Adoptante::factory()->create();
    $refugio = Refugio::factory()->create(['activo' => true]);
    $publicacion = Publicacion::factory()->create([
        'refugio_id' => $refugio->id,
        'estado' => 'activa',
    ]);
    Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado' => 'pendiente',
    ]);
    Adopcion::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado_adopcion' => 'activa',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('Panel de Administración');
    $response->assertSee('Adoptantes');
    $response->assertSee('Solicitudes pendientes');
    $response->assertViewHas('metricas', function ($metricas) {
        return $metricas['total_adoptantes'] >= 1
            && $metricas['total_refugios'] >= 1
            && $metricas['publicaciones_activas'] >= 1
            && $metricas['solicitudes_pendientes'] >= 1
            && $metricas['adopciones_activas'] >= 1;
    });
});

it('bloquea el acceso al dashboard admin a un adoptante', function () {
    $adoptante = User::factory()->create(['role' => 'adoptante']);

    $this->actingAs($adoptante)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('bloquea el acceso al dashboard admin a un refugio', function () {
    $refugioUser = User::factory()->create(['role' => 'refugio']);

    $this->actingAs($refugioUser)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

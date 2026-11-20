<?php

use App\Models\Adoptante;
use App\Models\Refugio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * HU-26: Admin ve listado de adoptantes y refugios.
 * Patrón: Feature test con RefreshDatabase (requerido por CI, HU-31).
 */
it('permite al admin ver el listado de adoptantes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $adoptante = Adoptante::factory()->create();

    $response = $this->actingAs($admin)->get(route('admin.adoptantes.index'));

    $response->assertOk();
    $response->assertSee($adoptante->alias);
    $response->assertSee($adoptante->user->email);
});

it('permite al admin ver el listado de refugios', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $refugio = Refugio::factory()->create();

    $response = $this->actingAs($admin)->get(route('admin.refugios.index'));

    $response->assertOk();
    $response->assertSee($refugio->nombre);
});

it('bloquea el acceso a un adoptante', function () {
    $adoptante = User::factory()->create(['role' => 'adoptante']);

    $this->actingAs($adoptante)
        ->get(route('admin.adoptantes.index'))
        ->assertForbidden();
});

it('bloquea el acceso a un refugio', function () {
    $refugioUser = User::factory()->create(['role' => 'refugio']);

    $this->actingAs($refugioUser)
        ->get(route('admin.refugios.index'))
        ->assertForbidden();
});

it('redirige al invitado al login', function () {
    $this->get(route('admin.adoptantes.index'))
        ->assertRedirect(route('login'));
});

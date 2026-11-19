<?php

use App\Models\User;
use App\Notifications\SolicitudStatusNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un adoptante puede recibir y ver una notificacion in-app', function () {
    $userAdoptante = User::factory()->create(['role' => 'adoptante']);

    // Simulamos que el sistema (HU-20) envía la notificación
    $userAdoptante->notify(new SolicitudStatusNotification('aceptada', '¡Felicidades! Tu solicitud fue aceptada.'));

    $this->assertDatabaseCount('notifications', 1);

    // Actuamos como el adoptante entrando a su bandeja
    $response = $this->actingAs($userAdoptante)->get(route('adoptante.notificaciones.index'));

    $response->assertOk();
    $response->assertSee('¡Felicidades! Tu solicitud fue aceptada.');
});

test('un adoptante puede marcar una notificacion como leida', function () {
    $userAdoptante = User::factory()->create(['role' => 'adoptante']);
    $userAdoptante->notify(new SolicitudStatusNotification('rechazada', 'Lo sentimos, tu solicitud fue rechazada.'));

    $notificacion = $userAdoptante->notifications()->first();

    $response = $this->actingAs($userAdoptante)->patch(route('adoptante.notificaciones.leer', $notificacion->id));

    $response->assertRedirect();

    // Verificamos que la notificación ya tenga fecha de lectura
    $this->assertNotNull($notificacion->fresh()->read_at);
});

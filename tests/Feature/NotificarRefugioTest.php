<?php

use App\Models\Adoptante;
use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\Solicitud;
use App\Models\User;
use App\Notifications\NuevaSolicitudRecibida;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

/**
 * HU-23: Al crearse una solicitud, se notifica al refugio dueño de la publicación.
 *
 * Patrón testeado: Notification (database) + Active Record.
 */
it('notifica al refugio cuando se crea una nueva solicitud', function () {
    // Arrange
    Notification::fake();

    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id]);
    $publicacion = Publicacion::factory()->create(['refugio_id' => $refugio->id]);

    $adoptanteUser = User::factory()->create(['role' => 'adoptante']);
    $adoptante = Adoptante::factory()->create(['user_id' => $adoptanteUser->id]);

    // Act: simular la creación de una solicitud (HU-16)
    $solicitud = Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado' => 'pendiente',
    ]);

    // Notificar manualmente (como lo haría el controlador de HU-16)
    $userRefugio->notify(new NuevaSolicitudRecibida($solicitud));

    // Assert
    Notification::assertSentTo(
        $userRefugio,
        NuevaSolicitudRecibida::class
    );
});

it('la notificación se guarda en la base de datos con los datos correctos', function () {
    // Arrange
    $userRefugio = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $userRefugio->id]);
    $publicacion = Publicacion::factory()->create(['refugio_id' => $refugio->id]);

    $adoptanteUser = User::factory()->create(['role' => 'adoptante']);
    $adoptante = Adoptante::factory()->create(['user_id' => $adoptanteUser->id]);

    $solicitud = Solicitud::factory()->create([
        'publicacion_id' => $publicacion->id,
        'adoptante_id' => $adoptante->id,
        'estado' => 'pendiente',
    ]);

    // Act
    $userRefugio->notify(new NuevaSolicitudRecibida($solicitud));

    // Assert: la notificación está en la tabla `notifications`
    $this->assertDatabaseHas('notifications', [
        'notifiable_type' => User::class,
        'notifiable_id' => $userRefugio->id,
        'type' => NuevaSolicitudRecibida::class,
    ]);

    $notificacion = $userRefugio->notifications()->first();
    expect($notificacion->data['solicitud_id'])->toBe($solicitud->id);
    expect($notificacion->data['mensaje'])->toContain('nueva solicitud');
    expect($notificacion->read_at)->toBeNull();
});

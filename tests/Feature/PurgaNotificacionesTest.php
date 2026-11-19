<?php

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

/**
 * HU-25: Las notificaciones con más de 90 días son podables (eliminables).
 *
 * Patrón testeado: MassPrunable + Active Record.
 *
 * Nota: se usa forceCreate() para asignar el UUID manualmente,
 * ya que `id` no está en $fillable (decisión de seguridad).
 */
it('elimina las notificaciones con más de 90 días', function () {
    // Arrange
    $user = User::factory()->create();

    $notificacionAntigua = Notification::forceCreate([
        'id' => (string) Str::uuid(),
        'type' => 'App\Notifications\NuevaSolicitudRecibida',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => json_encode(['mensaje' => 'antigua']),
        'created_at' => now()->subDays(100),
    ]);

    $notificacionReciente = Notification::forceCreate([
        'id' => (string) Str::uuid(),
        'type' => 'App\Notifications\NuevaSolicitudRecibida',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => json_encode(['mensaje' => 'reciente']),
        'created_at' => now()->subDays(10),
    ]);

    // Act
    $this->artisan('model:prune', ['--model' => [Notification::class]]);

    // Assert
    $this->assertDatabaseMissing('notifications', ['id' => $notificacionAntigua->id]);
    $this->assertDatabaseHas('notifications', ['id' => $notificacionReciente->id]);
});

it('no elimina notificaciones con menos de 90 días', function () {
    // Arrange
    $user = User::factory()->create();

    $notificacion = Notification::forceCreate([
        'id' => (string) Str::uuid(),
        'type' => 'App\Notifications\NuevaSolicitudRecibida',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => json_encode(['mensaje' => 'reciente']),
        'created_at' => now()->subDays(30),
    ]);

    // Act
    $this->artisan('model:prune', ['--model' => [Notification::class]]);

    // Assert
    $this->assertDatabaseHas('notifications', ['id' => $notificacion->id]);
});

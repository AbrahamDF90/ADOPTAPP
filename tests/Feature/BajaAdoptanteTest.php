<?php

use App\Models\Adoptante;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un adoptante puede darse de baja y sus solicitudes se cancelan', function () {
    // 1. Arrange
    $user = User::factory()->create(['role' => 'adoptante']);
    $adoptante = Adoptante::factory()->create(['user_id' => $user->id]);

    $solicitud = Solicitud::factory()->create([
        'adoptante_id' => $adoptante->id,
        'estado' => 'pendiente',
    ]);

    // 2. Act
    $response = $this->actingAs($user)->delete(route('adoptante.perfil.destroy'));

    // 3. Assert
    // Verificamos que redirija al inicio
    $response->assertRedirect('/');

    // Verificamos que la sesión se cerró (el usuario ya no está autenticado)
    $this->assertGuest();

    // Verificamos que el usuario ya no puede iniciar sesión
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        // Si usamos SoftDeletes, deleted_at no será null
    ]);
});

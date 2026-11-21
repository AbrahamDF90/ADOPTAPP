<?php

use App\Models\User;
use App\Models\Adoptante;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * HU-07: Validar la edición del perfil sin modificar DNI.
 * Patrón: Arrange-Act-Assert.
 */
test('un adoptante puede actualizar su perfil sin modificar su DNI', function () {
    Storage::fake('public');
    
    $user = User::factory()->create(['role' => 'adoptante']);
    $adoptante = Adoptante::create([
        'user_id' => $user->id,
        'dni' => '11223344',
        'alias' => 'ViejoAlias',
        'direccion' => 'Calle Vieja 123',
        'telefono' => '123456',
    ]);

    $response = $this->actingAs($user)->patch(route('adoptante.perfil.update'), [
        'dni' => '99999999', // Intento de cambio (ignorado)
        'alias' => 'NuevoAlias',
        'direccion' => 'Calle Nueva 456',
        'telefono' => '987654',
        'foto' => UploadedFile::fake()->image('nueva_foto.jpg'),
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('adoptantes', [
        'id' => $adoptante->id,
        'dni' => '11223344',
        'alias' => 'NuevoAlias',
    ]);

    $adoptante->refresh();
    Storage::disk('public')->assertExists($adoptante->foto);
});
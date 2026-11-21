<?php

use App\Models\Publicacion;
use App\Models\Refugio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('un refugio puede editar su propia publicacion', function () {
    Storage::fake('public');

    // Arrange: Creamos el refugio y su publicación
    $user = User::factory()->create(['role' => 'refugio']);
    $refugio = Refugio::factory()->create(['user_id' => $user->id]);
    $publicacion = Publicacion::factory()->create(['refugio_id' => $refugio->id]);

    // Act: Intentamos editar
    $response = $this->actingAs($user)->patch(route('refugio.publicaciones.update', $publicacion), [
        'especie' => 'Gato Persa',
        'sexo' => 'Hembra',
        'edad_aproximada' => '3 años',
        'foto' => UploadedFile::fake()->image('nueva_foto.jpg'),
    ]);

    // Assert: Verificamos los cambios
    $response->assertRedirect(route('refugio.dashboard'));
    $this->assertDatabaseHas('publicaciones', [
        'id' => $publicacion->id,
        'especie' => 'Gato Persa',
        'sexo' => 'Hembra',
    ]);
});

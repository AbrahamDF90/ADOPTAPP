<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // ¡ESTA ES LA LÍNEA MÁGICA QUE CREA LAS TABLAS EN EL CI!
    use RefreshDatabase;

    /**
     * Test básico de humo (Smoke Test).
     * Verifica que la página principal responda con un estado 200 OK.
     * 
     * Patrón aplicado: Arrange-Act-Assert (Preparar, Actuar, Verificar).
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
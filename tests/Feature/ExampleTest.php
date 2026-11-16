<?php

namespace Tests\Feature;

// 1. Importamos el trait RefreshDatabase
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // 2. Lo usamos para que Laravel cree las tablas en la BD en memoria antes del test
    use RefreshDatabase;

    /**
     * Test básico de humo (Smoke Test).
     * Verifica que la página principal responda con un estado 200 OK.
     * 
     * Patrón aplicado: Arrange-Act-Assert (Preparar, Actuar, Verificar).
     * Gracias a RefreshDatabase, la consulta a la tabla 'publicaciones' 
     * de HomeController no fallará por falta de tablas.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Act: Hacemos la petición GET a la ruta raíz
        $response = $this->get('/');

        // Assert: Verificamos que responda 200
        $response->assertStatus(200);
    }
}
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Configuración inicial para las pruebas
     */
    public function setUp(): void
    {
        parent::setUp();

        // Limpiar datos existentes
        Permission::truncate();
        Role::truncate();

        // Crear permisos y roles
        if (!Permission::where('name', 'ver')->exists()) {
            Permission::create(['name' => 'ver']);
        }

        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        // Crear un usuario y asignar rol
        $user = User::factory()->create();
        $user->assignRole('admin');
    }

    /**
     * Prueba para verificar que el middleware permite acceso a un usuario con rol o permiso válido
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_role_or_permission_middleware_allows_access()
    {
        $user = User::first();

        // Actuar como el usuario autenticado
        $response = $this->actingAs($user)->get('/api/test');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Middleware funcionando']);
    }

    /**
     * Prueba para verificar que el middleware niega acceso a un usuario sin rol o permiso válido
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_role_or_permission_middleware_denies_access()
    {
        // Crear un usuario sin roles ni permisos
        $user = User::factory()->create();

        // Actuar como el usuario sin roles
        $response = $this->actingAs($user)->get('/api/test');

        $response->assertStatus(403); // Prohibido
    }
}

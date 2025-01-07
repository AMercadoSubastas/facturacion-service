<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'Facturar Conceptos',
            'Facturar Lotes',
            'Crear User',
            'Editar User',
            'Eliminar User',
            'Crear Entidades',
            'Editar Entidades',
            'Eliminar Entidades',
            'JarvisFullAccess',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web', // Cambiar a 'web' o 'custom' según tu necesidad
            ]);
        }

        $roles = [
            'SysAdmin' => ['JarvisFullAccess'],
            'CrecoAdmin' => [
                'Facturar Conceptos',
                'Facturar Lotes',
                'Crear Entidades',
                'Editar Entidades',
                'Eliminar Entidades',
                'Crear User',
                'Editar User',
                'Eliminar User',
            ],
            'CrecoUser' => [
                'Facturar Conceptos',
                'Facturar Lotes',
                'Crear Entidades',
                'Editar Entidades',
                'Eliminar Entidades',
            ],
            'ConAdmin' => [
                'Facturar Conceptos',
                'Facturar Lotes',
                'Crear User',
                'Editar User',
                'Eliminar User'
            ],
            'ConUser' => [
                'Facturar Conceptos',
                'Facturar Lotes'
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web', // Cambiar a 'web' o 'custom' según tu necesidad
            ]);

            $role->syncPermissions($rolePermissions);
        }

        $this->command->info('Roles y permisos creados con éxito.');
    }
}

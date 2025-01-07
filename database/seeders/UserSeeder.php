<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'codnum' => 1,
                'usuario' => 'sysadmin',
                'nombre' => 'SysAdmin',
                'clave' => Hash::make('nopodraspasar'),
                'nivel' => 1,
                'email' => 'sysadmin@example.com',
                'perfil' => null,
                'activo' => 1,
                'role' => 'SysAdmin',
                'permissions' => ['JarvisFullAccess'], // Permisos específicos
            ],
            [
                'codnum' => 2,
                'usuario' => 'crecoadmin',
                'nombre' => 'CrecoAdmin',
                'clave' => Hash::make('password123'),
                'nivel' => 2,
                'email' => 'crecoadmin@example.com',
                'perfil' => null,
                'activo' => 1,
                'role' => 'CrecoAdmin',
                'permissions' => [], // Sin permisos directos adicionales
            ],
            [
                'codnum' => 3,
                'usuario' => 'crecouser',
                'nombre' => 'CrecoUser',
                'clave' => Hash::make('password123'),
                'nivel' => 3,
                'email' => 'crecouser@example.com',
                'perfil' => null,
                'activo' => 1,
                'role' => 'CrecoUser',
                'permissions' => [], // Sin permisos directos adicionales
            ],
        ];

        foreach ($users as $userData) {
            // Crear el usuario
            $user = User::create([
                'codnum' => $userData['codnum'],
                'usuario' => $userData['usuario'],
                'nombre' => $userData['nombre'],
                'clave' => $userData['clave'],
                'nivel' => $userData['nivel'],
                'email' => $userData['email'],
                'perfil' => $userData['perfil'],
                'activo' => $userData['activo'],
            ]);

            // Asignar el rol al usuario
            $role = Role::where('name', $userData['role'])->where('guard_name', 'web')->first();
            if ($role) {
                $user->assignRole($role->name);
            }

            // Asignar permisos específicos al usuario
            if (!empty($userData['permissions'])) {
                foreach ($userData['permissions'] as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->where('guard_name', 'web')->first();
                    if ($permission) {
                        $user->givePermissionTo($permission);
                    }
                }
            }
        }

        $this->command->info('Usuarios creados con roles y permisos asignados exitosamente.');
    }
}

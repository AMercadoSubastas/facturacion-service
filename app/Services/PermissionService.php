<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Obtener todos los permisos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPermissions()
    {
        return Permission::all();
    }

    /**
     * Crear un nuevo permiso.
     *
     * @param array $data
     * @return Permission
     */
    public function createPermission(array $data)
    {
        return Permission::create(['name' => $data['name']]);
    }
}

<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Obtener roles con búsqueda y paginación.
     *
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getRolesWithSearchAndPagination(?string $search, int $perPage = 10)
    {
        $query = Role::with('permissions'); // Relación con permisos

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Crear un nuevo rol.
     *
     * @param array $data
     * @return Role
     */
    public function createRole(array $data)
    {
        return Role::create(['name' => $data['name']]);
    }

    /**
     * Obtener todos los roles con permisos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRolesWithPermissions()
    {
        return Role::with('permissions')->get(); // Incluir permisos asociados
    }

    /**
     * Asignar permisos a un rol.
     *
     * @param string $roleName
     * @param array $permissions
     * @return Role
     */
    public function assignPermissionsToRole(string $roleName, array $permissions)
    {
        $role = Role::findByName($roleName);
        $role->syncPermissions($permissions); // Asocia los permisos al rol
        return $role;
    }

    /**
    * Obtener un rol por su ID, incluyendo sus permisos.
    *
    * @param int $id
    * @return Role|null
    */
    public function getRoleById(int $id)
    {
        return Role::with('permissions')->find($id);
    }

    /**
     * Actualizar un rol existente.
     *
     * @param int $id
     * @param array $data
     * @return Role|null
     */
    public function updateRole(int $id, array $data)
    {
        $role = Role::find($id);

        if (!$role) {
            return null; // Si no se encuentra el rol, devuelve null
        }

        $role->name = $data['name'];
        $role->save(); // Guarda el cambio en el nombre

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']); // Actualiza los permisos asociados
        }

        return $role;
    }

}

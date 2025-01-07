<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
    public function getRoles()
    {
        $roles = $this->roleService->getAllRolesWithPermissions();
        return response()->json($roles);
    }

    /**
     * Obtener roles con búsqueda y paginación.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search = $request->input('search'); // Obtener término de búsqueda
        $perPage = $request->input('per_page', 5); // Obtener número de elementos por página

        $roles = $this->roleService->getRolesWithSearchAndPagination($search, $perPage);

        return response()->json($roles);
    }

    // Crear un nuevo rol
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = $this->roleService->createRole(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $this->roleService->assignPermissionsToRole($role->name, $validated['permissions']);
        }

        return response()->json(['message' => 'Rol creado exitosamente', 'role' => $role], 201);
    }

    // Asignar permisos a un rol
    public function assignPermissions(Request $request, $roleName)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = $this->roleService->assignPermissionsToRole($roleName, $validated['permissions']);

        return response()->json(['message' => 'Permisos asignados exitosamente', 'role' => $role]);
    }

    public function getPermissionsByRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        return response()->json($role->permissions);
    }
    public function show($id)
    {
        $role = $this->roleService->getRoleById($id);

        if (!$role) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        return response()->json($role);
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id, // Asegúrate de que el nombre sea único excepto para el rol actual
            'permissions' => 'array', // Verifica que sea un array de permisos
            'permissions.*' => 'string|exists:permissions,name', // Valida que los permisos existen
        ]);

        $role = $this->roleService->updateRole($id, $validated);

        if (!$role) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        return response()->json(['message' => 'Rol actualizado exitosamente', 'role' => $role], 200);
    }



}

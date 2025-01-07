<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    // Obtener todos los permisos
    public function index()
    {
        $permissions = $this->permissionService->getAllPermissions();
        return response()->json($permissions);
    }

    // Crear un nuevo permiso
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = $this->permissionService->createPermission($validated);
        return response()->json(['message' => 'Permiso creado exitosamente', 'permission' => $permission], 201);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function redirectToGoogle()
    {
        return $this->userService->redirectToGoogle();
    }

    public function getConnectedUsers()
    {
        // Filtrar tokens válidos que no han expirado
        $connectedUsers = \DB::table('personal_access_tokens')
            ->join('usuarios', 'personal_access_tokens.tokenable_id', '=', 'usuarios.codnum')
            ->select('usuarios.codnum', 'usuarios.usuario', 'usuarios.nombre', 'personal_access_tokens.last_used_at')
            ->whereNotNull('personal_access_tokens.last_used_at')
            ->get();

        return response()->json(['connected_users' => $connectedUsers], 200);
    }

    public function handleGoogleCallback()
    {
        try {
            $token = $this->userService->handleGoogleCallback();
            if ($token) {
                return redirect()->to('http://localhost:8080/auth/callback?token=' . $token);
            }
        } catch (\Exception $e) {
            return redirect()->to('/login')->withErrors('Error al iniciar sesión con Google.');
        }
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:usuarios,email']);
        $sent = $this->userService->sendPasswordResetLink($request->email);

        if ($sent) {
            return response()->json(['message' => 'Enlace de restablecimiento enviado'], 200);
        }
        return response()->json(['message' => 'Error al enviar el enlace de restablecimiento'], 500);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $status = $this->userService->resetPassword(
            $request->email,
            $request->password,
            $request->token
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Contraseña restablecida con éxito'], 200)
            : response()->json(['message' => 'Error al restablecer la contraseña'], 500);
    }

    public function register(StoreUserRequest $request)
    {
        $user = $this->userService->registerUser($request->all());
        return response()->json(['message' => 'Usuario registrado correctamente', 'user' => $user], 201);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'clave' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|exists:roles,name',
            'activo' => 'required|boolean',
        ]);

        $user = $this->userService->createUserWithRole($validated);

        // Verificar permisos asignados al usuario
        $permissions = $user->getAllPermissions();
        if ($permissions->isEmpty()) {
            return response()->json(['error' => 'No se asignaron permisos al usuario.'], 400);
        }

        return response()->json(['message' => 'Usuario creado correctamente', 'user' => $user], 201);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'usuario' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'clave' => 'nullable|string|min:8|confirmed',
            'rol' => 'required|string|exists:roles,name',
            'activo' => 'required|boolean',
        ]);

        $user = $this->userService->updateUserWithRole($id, $validated);
        return response()->json(['message' => 'Usuario actualizado correctamente', 'user' => $user], 200);
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }

    public function show($codnum)
    {
        $user = $this->userService->getUserWithRolesAndPermissions($codnum);
        return response()->json(['user' => $user], 200);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = $this->userService->getAllUsersPaginated(10, $search);
        return response()->json($users);
    }

    public function getUserLevels()
    {
        $userLevels = $this->userService->getUserLevels();
        return response()->json($userLevels);
    }

    public function logout(Request $request)
    {
        $this->userService->logoutUser();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserLevel;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class UserService
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->getEmail())->where('activo', true)->first();

        if ($user) {
            Auth::login($user);
            if (Auth::check()) {
                return $user->createToken('auth_token')->plainTextToken;
            }
        }

        return response()->json(['error' => 'Usuario no autorizado o inactivo.'], 401);
    }

    public function sendPasswordResetLink($email)
    {
        $resetLink = $this->generateResetLink($email);
        // Código para enviar email omitido por brevedad
        return true;
    }

    private function generateResetLink($email)
    {
        $user = User::where('email', $email)->first();
        $token = Password::createToken($user);

        return 'http://localhost:8080/newpassword?token=' . $token . '&email=' . urlencode($email);
    }

    public function resetPassword($email, $password, $token)
    {
        return Password::reset(
            ['email' => $email, 'password' => $password, 'token' => $token],
            function ($user) use ($password) {
                $user->forceFill(['clave' => Hash::make($password)])->save();
            }
        );
    }

    public function registerUser(array $data)
    {
        $user = User::create([
            'usuario' => $data['usuario'],
            'nombre' => $data['nombre'],
            'clave' => Hash::make($data['clave']),
            'email' => $data['email'],
            'nivel' => $data['nivel'] ?? 0,
            'activo' => $data['activo'] ?? 1,
        ]);

        return $user;
    }

    public function createUserWithRole(array $data)
    {
        $user = User::create([
            'usuario' => $data['usuario'],
            'nombre' => $data['nombre'],
            'clave' => Hash::make($data['clave']),
            'email' => $data['email'],
            'nivel' => $data['nivel'] ?? 0,
            'activo' => $data['activo'],
        ]);

        // Asignar el rol
        $user->assignRole($data['rol']);

        // Obtener los permisos del rol
        $role = Role::findByName($data['rol']);
        if ($role) {
            $user->givePermissionTo($role->permissions);
        }

        return $user;
    }


    public function updateUserWithRole($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);

        if (isset($data['rol'])) {
            $user->syncRoles([$data['rol']]);
        }

        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function getUserWithRolesAndPermissions($codnum)
    {
        return User::with(['roles', 'permissions'])->findOrFail($codnum);
    }

    public function getAllUsersPaginated($perPage = 10, $search = null)
    {
        $query = User::with('roles');
        if ($search) {
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('usuario', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        }

        return $query->paginate($perPage);
    }

    public function getUserLevels()
    {
        return UserLevel::all(['userlevelid', 'userlevelname']);
    }

    public function logoutUser()
    {
        Auth::logout();
    }
}

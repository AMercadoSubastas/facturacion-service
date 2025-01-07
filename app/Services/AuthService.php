<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class AuthService
{
    public function login(array $credentials)
    {
        if (Auth::attempt(['usuario' => $credentials['usuario'], 'password' => $credentials['clave']])) {
            session()->regenerate();
    
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Actualizar manualmente `last_used_at`
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken) {
                $accessToken->update(['last_used_at' => now()]);
            }
    
            // Obtener roles y permisos
            $roles = $user->getRoleNames(); // Colección de nombres de roles
            $permissions = $user->getAllPermissions()->pluck('name'); // Colección de nombres de permisos
    
            return [
                'message' => 'Login successful',
                'user' => $user,
                'roles' => $roles,
                'permissions' => $permissions,
                'token' => $token,
            ];
        }
    
        return ['error' => 'Usuario o clave incorrectos'];
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return ['message' => 'Logout successful'];
    }

    public function getConnectedUsers()
    {
        return DB::table('personal_access_tokens')
            ->join('usuarios', 'personal_access_tokens.tokenable_id', '=', 'usuarios.codnum')
            ->where('last_used_at', '>=', Carbon::now()->subMinutes(5)) // Tokens usados en los últimos 5 minutos
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now()); // Tokens que no han expirado
            })
            ->select('usuarios.codnum', 'usuarios.usuario', 'usuarios.nombre', 'personal_access_tokens.last_used_at')
            ->get();
    }
    
}

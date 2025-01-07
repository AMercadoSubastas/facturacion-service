<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        // Valida 'usuario' y 'clave'
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'clave' => 'required|string',
        ]);

        $response = $this->authService->login($credentials);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Usuario o clave incorrectos'], 401);
        }

        return response()->json($response); // Retorna la respuesta con datos del usuario
    }

    public function logout(Request $request)
    {
        $response = $this->authService->logout();
        return response()->json($response);
    }
    public function getConnectedUsers()
    {
        $connectedUsers = $this->authService->getConnectedUsers();
        return response()->json($connectedUsers);
    }
    

}

<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\EntidadService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\EntidadRequest;


class EntidadController extends Controller
{
    protected $entidadService;

    // Inyectar EntidadService
    public function __construct(EntidadService $entidadService)
    {
        $this->entidadService = $entidadService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $per_page = $request->get('per_page', 10);
            $response = $this->entidadService->index(['search' => $search, 'per_page' => $per_page]);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }



    public function store(Request $request)
{
    try {
        $data = $request->validate([
            'razsoc' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'pisodto' => 'nullable|string|max:10',
            'codpais' => 'required|integer',
            'codprov' => 'required|integer',
            'codloc' => 'required|integer',
            'codpost' => 'required|string|max:10',
            'tellinea' => 'nullable|string|max:20',
            'telcelu' => 'nullable|string|max:20',
            'tipoent' => 'required|integer',
            'tipoiva' => 'required|integer',
            'cuit' => 'required|string|max:13',
            'calif' => 'nullable|integer',
            'fecalta' => 'nullable|date',
            'contacto' => 'nullable|string|max:255',
            'mailcont' => 'nullable|email|max:255',
            'tipoind' => 'nullable|integer',
        ]);

        $response = $this->entidadService->store($data);
        return response()->json($response, 201);
    } catch (\Exception $e) {
        Log::error('Error al crear la entidad: ' . $e->getMessage());
        return response()->json(['error' => 'Error al crear la entidad.'], 400);
    }
}

    public function show(Request $request, string $cuit)
    {
        try {
            $response = $this->entidadService->show($cuit);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    public function update(Request $request, int $codnum)
{
    try {
        $data = $request->validate([
            'razsoc' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'pisodto' => 'nullable|string|max:10',
            'codpais' => 'required|integer',
            'codprov' => 'required|integer',
            'codloc' => 'required|integer',
            'codpost' => 'required|string|max:10',
            'tellinea' => 'nullable|string|max:20',
            'telcelu' => 'nullable|string|max:20',
            'tipoent' => 'required|integer',
            'tipoiva' => 'required|integer',
            'calif' => 'nullable|integer',
            'fecalta' => 'nullable|date',
            'contacto' => 'nullable|string|max:255',
            'mailcont' => 'nullable|email|max:255',
            'tipoind' => 'nullable|integer',
        ]);
        $response = $this->entidadService->update($data, $codnum);
        return response()->json($response, 200);
    } catch (\Exception $e) {
        Log::error('Error al actualizar la entidad: ' . $e->getMessage());
        return response()->json(['error' => 'Error al actualizar la entidad.'], 400);
    }
}


    public function destroy(int $codnum)
    {
        try {
            $response = $this->entidadService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    public function searchConstancia(Request $request)
    {
        try {
            $cuit = $request->input('cuit');
            $response = $this->entidadService->searchConstancia($cuit);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
    public function updateEstado(Request $request, int $codnum)
    {
        Log::info($codnum);

        try {
            $data = $request->only(['activo']);
            $response = $this->entidadService->updateEstado($codnum, $data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
    public function showByCodnum(int $codnum)
    {
        try {
            $response = $this->entidadService->showByCodnum($codnum);
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}

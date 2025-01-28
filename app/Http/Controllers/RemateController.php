<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remates;
use App\Models\Entidades;
use App\Services\RemateService;
use App\Http\Requests\RematesRequest;

class RemateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only(['search', 'per_page', 'sort_by', 'sort_order']);
            $remateService = new RemateService();
            $response = $remateService->index($data);
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(RematesRequest $request)
    {
        try {
            $data = $request->only(['ncomp', 'direccion', 'observacion', 'codpais','codprov','codloc']);
            $bancoService = new RemateService();
            $response = $bancoService->store($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
    
    public function show(int $ncomp)
    {
        $data = Remates::where('ncomp', $ncomp)
            ->with(['codpais', 'codprov', 'codloc', 'codcli', 'tipoind'])
            ->first();
    
        if (!$data) {
            throw new \Exception("No existe la Subasta");
        }
    
        $clientesActivos = Entidades::where('activo', 1)->get();
    
        return response()->json([
            'subasta' => $data,
            'clientes' => $clientesActivos,
        ]);
    }

    public function obtenerLotes(Request $request, int $codnum)
    {
        try {
            $request->validate([
                'codnum' => 'numeric',
            ]);
    
            $params = $request->only(['per_page', 'sort_by', 'sort_order', 'search', 'page']);
            $remateService = new RemateService();
            $lotes = $remateService->obtenerLotes($codnum, $params);
    
            return response()->json($lotes, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $codnum)
    {
        try {
            $data = $request->all(); // Obtener todos los datos enviados
            $remate = Remates::where('codnum', $codnum)->first();
    
            if (!$remate) {
                throw new \Exception("No existe la Subasta");
            }
    
            $remate->update($data); // Actualizar los campos en la base de datos
            return response()->json(['message' => 'Subasta actualizada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $codnum)
    {
        try {
            $bancoService = new RemateService();
            $response = $bancoService->destroy($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}

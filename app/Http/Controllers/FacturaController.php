<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacturaService;
use App\Services\AFIPService;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only(['tcomp', 'serie', 'ncomp', 'cliente', 'codrem', 'estado', 'emitido', 'totbruto', 'nrengs', 'usuario', 'CAE', 'CAEFchVto']);
            $facturaService = new FacturaService();
            $response = $facturaService->index($data);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $codnum)
    {
        try {
            $request->validate([
                'codnum' => 'numeric',
            ]);
            $facturaService = new FacturaService();

            $response = $facturaService->show($codnum);
            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    public function facturar(Request $request)
    {
        try {
            $data = $request->only('comprobante');
            $facturaService = new FacturaService();
            $result = $facturaService->crearFacturaA($data);

            return response()->json($result);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

}

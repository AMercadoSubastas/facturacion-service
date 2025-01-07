<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReporteService;
use Illuminate\Support\Facades\Log;

class ReporteController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    public function generateFactura(Request $request)
    {
        $data = $request->validate([
            'tcomp' => 'required|integer',
            'serie' => 'required|integer',
            'ncomp' => 'required|integer',
        ]);

        Log::info($data); 

        try {
            return $this->reporteService->generarFactura($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function index(Request $request)
    {
        try {
            $data = $request->only(['tcomp', 'serie', 'ncomp']);
            $facturaService = new ReporteService();
            $response = $facturaService->generarFactura($data);

            return response($response, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}

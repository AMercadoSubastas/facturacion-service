<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CobranzaIntegradaService;

class CobranzaIntegradaController extends Controller
{
    private $cobranzaService;

    public function __construct(CobranzaIntegradaService $cobranzaService)
    {
        $this->cobranzaService = $cobranzaService;
    }

    /**
     * Endpoint para obtener el token.
     */
    public function obtenerToken()
    {
        try {
            $token = $this->cobranzaService->obtenerToken();
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint para verificar el estado de conexión.
     */
    public function verificarConexion()
    {
        try {
            $status = $this->cobranzaService->verificarConexion();
            return response()->json(['status' => $status]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint para publicar una deuda.
     */
    public function publicarDeuda(Request $request)
    {
        $request->validate([
            'nroConvenio' => 'required|integer',
            'nroSeguimiento' => 'required|integer',
            'tipoPublicacion' => 'required|string|in:F,I',
            'detalle' => 'required|array|min:1',
            'detalle.*.tipoIdCliente' => 'nullable|string|max:4',
            'detalle.*.nroIdCliente' => 'required|string',
            'detalle.*.nroDocumentoCliente' => 'required|string',
            'detalle.*.tipoDocumento' => 'required|string|max:2',
            'detalle.*.nroDocumento' => 'required|string',
            'detalle.*.fechaVigencia' => 'nullable|date',
            'detalle.*.importe1' => 'required|numeric',
            'detalle.*.fechaVencimiento1' => 'nullable|date',
            'detalle.*.importe2' => 'nullable|numeric',
            'detalle.*.fechaVencimiento2' => 'nullable|date',
            'detalle.*.importe3' => 'nullable|numeric',
            'detalle.*.fechaVencimiento3' => 'nullable|date',
            'detalle.*.nombreCliente' => 'nullable|string',
            'detalle.*.codMoneda' => 'required|string|max:3',
            'detalle.*.leyenda1' => 'nullable|string',
            'detalle.*.leyenda2' => 'nullable|string',
            'detalle.*.marcaChequeTercero' => 'nullable|string|in:S,N',
            'detalle.*.marcaChequeDiferido' => 'nullable|string|in:S,N',
            'detalle.*.fechaDiferimiento' => 'nullable|date',
            'detalle.*.cantidadDiasDiferimiento' => 'nullable|string',
        ]);

        try {
            $data = $request->all();
            $response = $this->cobranzaService->publicarDeuda($data);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function consultarEstadoDeuda(Request $request, $nroSeguimiento, $nroConvenio)
    {
        try {
            // Llamar al servicio para consultar el estado de la deuda
            $response = $this->cobranzaService->consultarEstadoDeuda($nroSeguimiento, $nroConvenio);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint para listar las publicaciones de deuda.
     */
    public function listarPublicaciones(Request $request, $nroSeguimiento, $nroConvenio)
    {
        try {
            // Llamar al servicio para listar las publicaciones
            $response = $this->cobranzaService->listarPublicaciones($nroSeguimiento, $nroConvenio);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * Endpoint para consultar pagos realizados.
    */
    public function consultarPagos($nroConvenio, $codMoneda, $fecha, $nroSeguimiento)
    {
        try {
            // Llamar al servicio para consultar los pagos
            $response = $this->cobranzaService->consultarPagos($nroConvenio, $codMoneda, $fecha, $nroSeguimiento);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint para consultar deudas QR.
     */
    public function consultarDeudaQR(Request $request, $nroSeguimiento, $nroConvenio)
    {
        $request->validate([
            'tipoIdCliente' => 'nullable|string|max:4',
            'nroIdCliente' => 'nullable|string',
            'nroDocumentoCliente' => 'nullable|string',
            'tipoDocumento' => 'nullable|string|max:2',
            'nroDocumento' => 'nullable|string',
            'fecActualizacionQRDesde' => 'nullable|date',
        ]);

        try {
            // Recoger parámetros de la solicitud
            $params = $request->only([
                'tipoIdCliente',
                'nroIdCliente',
                'nroDocumentoCliente',
                'tipoDocumento',
                'nroDocumento',
                'fecActualizacionQRDesde',
            ]);

            // Llamar al servicio para obtener los datos
            $response = $this->cobranzaService->consultarDeudaQR($nroSeguimiento, $nroConvenio, $params);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

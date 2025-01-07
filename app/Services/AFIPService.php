<?php

namespace App\Services;

use Afip;
use Illuminate\Support\Facades\Log;

class AFIPService
{
    protected $afip;

    public function __construct()
    {
        $certPath = app_path('Certificados/SubastasV8_prod.crt');
        $keyPath = app_path('Certificados/SubastasV8_prod.key');

        // Validar que los archivos existen
        if (!file_exists($certPath) || !file_exists($keyPath)) {
            throw new \Exception("Certificados no encontrados en las rutas especificadas");
        }

        $cuit = env('SESSION_AFIP_CERT_CUIT');

        // Validar que el CUIT esté configurado
        if (!$cuit || !preg_match('/^\d{11}$/', $cuit)) {
            throw new \Exception("El CUIT no está configurado o no es válido. Asegúrate de definir SESSION_AFIP_CERT_CUIT en .env");
        }

        $this->afip = new Afip([
            'CUIT' => $cuit,
            'cert' => $certPath,
            'key' => $keyPath,
            'production' => env('SESSION_AFIP_ENVIRONMENT', true), // true para producción, false para testing
            'res_folder' => storage_path('afip/tmp'), // Carpeta para recursos temporales
        ]);
    }

    public function autenticarWebService()
    {
        $username = env('SESSION_AFIP_NAME');
        $password = env('SESSION_AFIP_PASSWORD');
        $alias = env('SESSION_AFIP_ALIAS');
        $wsid = env('SESSION_AFIP_SERVICIO');

        try {
            // Crear la autorización
            $res = $this->afip->CreateWSAuth($username, $password, $alias, $wsid);
            Log::info("Autenticación exitosa con el servicio AFIP", ['response' => $res]);
            return "Conectado";
        } catch (\Exception $e) {
            Log::error("Error al autenticar el WS de AFIP: " . $e->getMessage());
            return response()->json(['error' => 'Error al autenticar el WS de AFIP'], 500);
        }
    }

    public function afipData($ptoVenta, $cbteTipo)
    {
        try {
            $response = $this->afip->ElectronicBilling->GetLastVoucher($ptoVenta, $cbteTipo);
            Log::info("Último comprobante obtenido", ['response' => $response]);
            return $response;
        } catch (\Exception $e) {
            Log::error("Error al obtener el último comprobante: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener el último comprobante'], 500);
        }
    }

    public function enviarFactura(array $data = [])
    {
        try {
            $result = $this->afip->ElectronicBilling->CreateVoucher($data);
            Log::info("Factura enviada con éxito", ['response' => $result]);
            return $result;
        } catch (\Exception $e) {
            Log::error("Error al enviar la factura: " . $e->getMessage());
            return response()->json(['error' => 'Error al enviar la factura'], 500);
        }
    }

    public function searchConstancia($cuit)
    {
        try {
            $nroCuit = str_replace('-', '', $cuit);

            // Validar el formato del CUIT
            if (!preg_match('/^\d{11}$/', $nroCuit)) {
                throw new \Exception("El CUIT debe tener 11 dígitos.");
            }

            $this->autenticarWebService();
            $response = $this->afip->RegisterInscriptionProof->GetTaxpayerDetails($nroCuit);

            Log::info("Datos de constancia recuperados", ['response' => $response]);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error("Error al buscar la constancia: " . $e->getMessage());
            return response()->json(['error' => 'Error al buscar la constancia'], 500);
        }
    }
}

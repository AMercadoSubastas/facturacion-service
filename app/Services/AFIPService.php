<?php

namespace App\Services;

use Afip;
use Illuminate\Support\Env;

class AFIPService
{
    protected $afip;

    public function __construct()
    {
        $certPath = file_get_contents(app_path('Certificados/SubastasTesting_test.crt'));
        $keyPath = file_get_contents(app_path('Certificados/SubastasTesting_test.key'));

        $this->afip = new Afip(array(
            'CUIT' => env('SESSION_AFIP_CERT_CUIT'), // Tu CUIT
            'cert' => $certPath,
            'key' => $keyPath,
            'production' => env('APP_ENV') == 'production', // Cambia a true para producción
            'res_folder' => __DIR__ . '/tmp/', // Carpeta temporal para guardar las respuestas
        ));
    }

    public function autenticarWebService()
    {
        // Datos de autenticación
        $username = env('SESSION_AFIP_NAME'); // Tu usuario de AFIP
        $password = env('SESSION_AFIP_PASSWORD'); // Tu contraseña de AFIP
        $alias = env('SESSION_AFIP_ALIAS'); // Alias del certificado
        $wsid = env('SESSION_AFIP_SERVICIO'); // ID del web service a autorizar (Factura Electrónica)

        try {
            // Crear la autorización
            $res = $this->afip->CreateWSAuth($username, $password, $alias, $wsid);

            // Mostrar el resultado de la autorización
            var_dump($res);
        } catch (\Exception $e) {
            echo 'Error al autorizar el WS: ' . $e->getMessage();
        }
    }

    public function afipData($ptoVenta ,$cbteTipo)
    {
        return $this->afip->ElectronicBilling->GetLastVoucher($ptoVenta ,$cbteTipo);
    }

    public function enviarFactura(array $data = [])
    {

        try {
            $result = $this->afip->ElectronicBilling->CreateVoucher($data);
            return $result;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}

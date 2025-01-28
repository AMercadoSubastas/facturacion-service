<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class CobranzaIntegradaService
{
    private $httpClient;
    private $tokenUri = 'https://cap-enterprise-auth-controller-b2b.hgal.bancogalicia.com.ar/v1/pom/realms/enterprise-auth/openid-connect/create-token';
    private $statusUri = 'https://cobr-cobranzascore-b2b.hgal.bancogalicia.com.ar:8443/v1/pom/cobr/orquestador-galicia/connection/status';
    private $deudaUri = 'https://cobr-cobranzascore-b2b.hgal.bancogalicia.com.ar:8443/v1/pom/cobr/orquestador-galicia/publication';
    private $paymentsUri = 'https://cobr-cobranzascore-b2b.hgal.bancogalicia.com.ar:8443/v1/pom/cobr/orquestador-galicia/payments';


    public function __construct()
    {
        $this->httpClient = new Client([
            'cert' => app_path('Certificados/30718033612_11_AA_D1_FF_F1_B5_7C_7C_48_02_11_1E_B1_B2_C7_29.pem'), // Certificado combinado
            'verify' => false, // Ignora SSL en pruebas
        ]);
    }

    /**
     * Obtener el token de acceso.
     */
    public function obtenerToken()
    {
        try {
            $response = $this->httpClient->post($this->tokenUri, [
                'headers' => [
                    'certificate' => '30949535947',
                    'id_channel' => 'b2b',
                    
                    'serial_id_cert' => '11aad1fff1b57c7c4802111eb1b2c729',
                   //'serial_id_cert' => '120dc6213bf9ebd3a96be07cca17c2c2',
                    'api-key' => '1bd541be2718b9e943b6fe84b2ce5673',
                    'Content-Type' => 'application/json',
                    'Accept' => '*/*',
                    'Authorization' => 'Basic ' . base64_encode('30949535947:password'),
                ],
                'body' => json_encode([
                    'grant_type' => 'password',
                    'client_id' => '30949535947',
                ]),
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (isset($body['data'][0]['access_token'])) {
                return $body['data'][0]['access_token'];
            }

            throw new \Exception("No se encontró access_token en la respuesta.");
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = $e->getResponse()->getBody()->getContents();
                Log::error("Error al obtener token: {$statusCode} - {$errorBody}");
                throw new \Exception("Error al obtener token: {$errorBody}");
            } else {
                throw new \Exception("Error general: " . $e->getMessage());
            }
        }
    }

    /**
     * Verificar el estado de conexión utilizando el token de acceso.
     */
    public function verificarConexion()
    {
        try {
            // Obtener el token de acceso
            $accessToken = $this->obtenerToken();

            $response = $this->httpClient->get($this->statusUri, [
                'headers' => [
                    'id_channel' => 'b2b',
                    'app_id' => '08e032ba',
                    'app_key' => '4bcc902bef62b67dc9cdcdf0b84e5a7a',
                    'Accept' => 'text/plain',
                    'Authorization' => "Bearer {$accessToken}", // Token de acceso
                ],
            ]);

            $body = $response->getBody()->getContents();
            Log::info("Respuesta de conexión: " . $body);

            return $body;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = $e->getResponse()->getBody()->getContents();
                Log::error("Error al verificar conexión: {$statusCode} - {$errorBody}");
                throw new \Exception("Error al verificar conexión: {$errorBody}");
            } else {
                throw new \Exception("Error general: " . $e->getMessage());
            }
        }
    }

    /**
     * Publicar una deuda.
     */
    public function publicarDeuda(array $data)
    {
        try {
            // Obtener el token de acceso
            $accessToken = $this->obtenerToken();

            $response = $this->httpClient->post($this->deudaUri, [
                'headers' => [
                    'id_channel' => 'b2b',
                    'app_id' => '08e032ba',
                    'app_key' => '4bcc902bef62b67dc9cdcdf0b84e5a7a',
                    'Content-Type' => 'application/json',
                    'Accept' => 'text/plain',
                    'Authorization' => "Bearer {$accessToken}",
                ],
                'json' => $data,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            Log::info("Respuesta publicación deuda: " . json_encode($body));

            return $body;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = $e->getResponse()->getBody()->getContents();
                Log::error("Error al publicar deuda: {$statusCode} - {$errorBody}");
                throw new \Exception("Error al publicar deuda: {$errorBody}");
            } else {
                throw new \Exception("Error general: " . $e->getMessage());
            }
        }
    }

    /**
     * Estado De la Deuda
     */
    public function consultarEstadoDeuda($nroSeguimiento, $nroConvenio)
    {
        try {
            // Obtener el token de acceso
            $accessToken = $this->obtenerToken();

            $url = "https://cobr-cobranzascore-b2b.hgal.bancogalicia.com.ar:8443/v1/pom/cobr/orquestador-galicia/publication/status/{$nroSeguimiento}/{$nroConvenio}";
     
            $response = $this->httpClient->get($url, [
                'headers' => [
                    'id_channel' => 'b2b',
                    'app_id' => '08e032ba',
                    'app_key' => '4bcc902bef62b67dc9cdcdf0b84e5a7a',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$accessToken}",
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            Log::info("Respuesta consulta de estado deuda: " . json_encode($body));
     
            return $body;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = $e->getResponse()->getBody()->getContents();
                Log::error("Error al consultar estado deuda: {$statusCode} - {$errorBody}");
                throw new \Exception("Error al consultar estado deuda: {$errorBody}");
            } else {
                throw new \Exception("Error general: " . $e->getMessage());
            }
        }
    }
    /**
     * Listar publicaciones de deuda.
     */
    public function listarPublicaciones($nroSeguimiento, $nroConvenio)
    {
        try {
            // Obtener el token de acceso
            $accessToken = $this->obtenerToken();

            $url = "https://cobr-cobranzascore-b2b.hgal.bancogalicia.com.ar:8443/v1/pom/cobr/orquestador-galicia/publication/list/{$nroSeguimiento}/{$nroConvenio}";

            $response = $this->httpClient->get($url, [
                'headers' => [
                    'id_channel' => 'b2b',
                    'app_id' => '08e032ba',
                    'app_key' => '4bcc902bef62b67dc9cdcdf0b84e5a7a',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$accessToken}",
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            Log::info("Respuesta listar publicaciones: " . json_encode($body));

            return $body;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = $e->getResponse()->getBody()->getContents();
                Log::error("Error al listar publicaciones: {$statusCode} - {$errorBody}");
                throw new \Exception("Error al listar publicaciones: {$errorBody}");
            } else {
                throw new \Exception("Error general: " . $e->getMessage());
            }
        }
    }

    /**
    * Consultar pagos realizados.
    */
    public function consultarPagos($nroConvenio, $codMoneda, $fecha, $nroSeguimiento)
    {
        try {
            // Obtener el token de acceso
            $accessToken = $this->obtenerToken();

            // Construir la URL de consulta de pagos
            $url = "{$this->paymentsUri}/{$nroConvenio}/{$codMoneda}/{$fecha}/{$nroSeguimiento}";

            // Realizar la solicitud GET
            $response = $this->httpClient->get($url, [
                'headers' => [
                    'id_channel' => 'b2b',
                    'app_id' => '08e032ba',
                    'app_key' => '4bcc902bef62b67dc9cdcdf0b84e5a7a',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$accessToken}",
                ],
            ]);

            // Procesar la respuesta
            $body = json_decode($response->getBody()->getContents(), true);
            Log::info("Respuesta consulta de pagos: " . json_encode($body));

            return $body;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = $e->getResponse()->getBody()->getContents();
                Log::error("Error al consultar pagos: {$statusCode} - {$errorBody}");
                throw new \Exception("Error al consultar pagos: {$errorBody}");
            } else {
                throw new \Exception("Error general: " . $e->getMessage());
            }
        }
    }

    /**
     * Consultar deudas con QR.
     */
    public function consultarDeudaQR($nroSeguimiento, $nroConvenio, $params)
    {
        try {
            // Obtener el token de acceso
            $accessToken = $this->obtenerToken();

            // Construir la URL con query params
            $queryString = http_build_query($params);
            $url = "https://cobr-cobranzascore-b2b.hgal.bancogalicia.com.ar:8443/v1/paas/cobr/orquestador-galicia/publication/listqr/{$nroSeguimiento}/{$nroConvenio}?{$queryString}";

            // Realizar la solicitud GET
            $response = $this->httpClient->get($url, [
                'headers' => [
                    'id_channel' => 'b2b',
                    'app_id' => '08e032ba',
                    'app_key' => '4bcc902bef62b67dc9cdcdf0b84e5a7a',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$accessToken}",
                ],
            ]);

            // Procesar la respuesta
            $body = json_decode($response->getBody()->getContents(), true);
            Log::info("Respuesta consulta deudas QR: " . json_encode($body));

            return $body;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = $e->getResponse()->getBody()->getContents();
                Log::error("Error al consultar deudas QR: {$statusCode} - {$errorBody}");
                throw new \Exception("Error al consultar deudas QR: {$errorBody}");
            } else {
                throw new \Exception("Error general: " . $e->getMessage());
            }
        }
    }

     
}

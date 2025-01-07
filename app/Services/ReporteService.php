<?php

namespace App\Services;

use Exception;

use App\Models\Cabfac;
use App\Models\Detfac;
use App\Models\Remates;
use App\Models\Tipoivas;
use App\Models\Entidades;
use App\Models\Lotes;

use Barryvdh\DomPDF\Facade\Pdf;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReporteService
{
    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 1;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'asc';
        $query = Cabfac::query();

        // Aplicar orden
        $query->with('codrem')->orderBy($sort_by, $sort_order);

        return $query->paginate($per_page);
    }

    public function generarFactura(array $data)
    {
        Log::info('Starting factura generation', $data);
        try {
            Log::info('Factura data:', [
                'tcomp' => $data['tcomp'],
                'serie' => $data['serie'],
                'ncomp' => $data['ncomp']
            ]);

            // Obtener cabecera de la factura
            $cabfac = Cabfac::where('tcomp', $data['tcomp'])
                                ->where('serie', $data['serie'])
                                ->where('ncomp', $data['ncomp'])
                                ->firstOrFail();
            Log::info('Cabecera de factura encontrada:', [$cabfac]);

            // Obtener los detalles de la factura
            $detalles = Detfac::where('tcomp', $data['tcomp'])
                                ->where('serie', $data['serie'])
                                ->where('ncomp', $data['ncomp'])
                                ->get();
            Log::info('Detalles de la factura obtenidos:', [$detalles]);

            // Obtener información del cliente
            $cliente = Entidades::where('codnum', $cabfac['cliente'])->first();
            Log::info('Cliente de la factura obtenidos:', [$cliente]);

            // Obtener remate
            $remates = Remates::where('ncomp', $data['ncomp'])->first();
            Log::info('Remates de la factura obtenidos:', [$remates]);

            // Obtener tipo de IVA
            $tipoIva = Tipoivas::where('codnum', $cliente->tipoiva)->first();
            Log::info('TipoIva de la factura obtenidos:', [$tipoIva]);

            $nrodoc = $cabfac->nrodoc;
            if (preg_match('/^([A-Z])(\d{4})-(\d{8})$/', $nrodoc, $matches)) {
                $letra = $matches[1];
                $puntoVenta = $matches[2];
                $numeroComp = $matches[3];
            } else {
                throw new \Exception("Formato de nrodoc inválido.");
            }

            $puntoVentaQR = ltrim($puntoVenta, '0');
            $numeroCompQR = ltrim($numeroComp, '0');

            $productos = $detalles->map(function ($detalle) {
                return [
                    'lote' => $detalle->codlote,
                    'nombre' => $detalle->descrip,
                    'subtotal' => $detalle->neto
                ];
            });
            Log::info(str_replace('-', '', $cliente->cuit));
            $datosQR = [
                'ver' => 1,
                'fecha' => $cabfac->fecdoc,
                'cuit' => 30718033612,
                'ptoVta' => (int) $puntoVentaQR,
                'tipoCmp' => 1,
                'nroCmp' => (int) $numeroCompQR,
                'importe' => (float) number_format($cabfac->totbruto, 2, '.', ''),
                'moneda' => 'PES',
                'ctz' => 1,
                'tipoDocRec' => 80,
                'nroDocRec' => intval(str_replace('-', '', $cliente->cuit)),
                'tipoCodAut' => 'E',
                'codAut' => (int) $cabfac->CAE,
            ];

            Log::info($datosQR);
            $jsonDatos = json_encode($datosQR);
            $datosBase64 = base64_encode($jsonDatos);
            $urlQR = "https://www.afip.gob.ar/fe/qr/?p=$datosBase64";

            Log::info($urlQR);

            // Generación del código QR
            $qrCode = QrCode::create($urlQR)
                ->setSize(300)
                ->setMargin(10);

            // Guardar el código QR en una imagen
            $qrWriter = new PngWriter();
            $result = $qrWriter->write($qrCode);
            $qrPath = storage_path('app/public/qr_afip.png');

            // Guardar la imagen en el disco
            file_put_contents($qrPath, $result->getString());
            $data['qr_path'] = $qrPath;

            // Preparar los datos adicionales para la vista de la factura
            $facturaData = [
                'cabecera' => $cabfac,
                'detalles' => $detalles,
                'productos' => $productos,
                'let_comp' => $letra,
                'punto_venta' => $puntoVenta,  // Mantener el punto de venta con ceros
                'comp_nro' => $numeroComp,  // Mantener el número de comprobante con ceros
                'fecha_emision' => $cabfac->fecdoc,
                'razon_social' => $cliente->razsoc ?? 'No disponible',
                'telefono' => $cliente->telcelu ?? 'N/A',
                'domicilio' => $cliente->calle ?? 'N/A',
                'code_postal' => $cliente->codpost ?? 'N/A',
                'nro_domicilio' => $cliente->numero ?? 'N/A',
                'email' => $cliente->mailcont ?? 'N/A',
                'cuit' => $cliente->cuit ?? 'N/A',
                'condicion_venta' => 'Contado',
                'condicion_iva' => $tipoIva->descrip ?? 'No disponible',
                'ubi_subasta' => $remates->direccion,
                'fecha_subasta' => $remates->fecreal,
                'id_subasta' => $remates->ncomp,
                'qr_path' => $data['qr_path'],
                'opciones_pago' => [
                    'transferencia_bancaria' => [
                        'banco' => 'Banco BBVA',
                        'cuenta' => '123-009694/0',
                        'cbu' => '0170123020000000969406',
                        'alias' => ': AM.SUBASTAS'
                    ],
                    'cheques' => 'Pago con cheques disponibles en 30 días'
                ],
                'neto_21' => $cabfac->totneto21,
                'neto_10_5' => $cabfac->totneto105,
                'iva_21' => $cabfac->totiva21,
                'iva_10_5' => $cabfac->totiva105,
                'uso_plataforma' => $cabfac->totcomis,
                'gs_adm' => 0,
                'importe_total' => $cabfac->totbruto,
                'cae' => $cabfac->CAE,
                'fecha_vto_cae' => $cabfac->CAEFchVto,
            ];

            // Generar PDF con los datos
            $pdf = Pdf::loadView('factura', $facturaData);

            return $pdf->stream('factura.pdf');
        } catch (ModelNotFoundException $e) {
            throw new \Exception("Factura no encontrada.");
        } catch (\Exception $e) {
            throw new \Exception("Error al generar la factura: " . $e->getMessage());
        }
    }

    
   
}

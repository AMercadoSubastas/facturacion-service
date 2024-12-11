<?php

namespace App\Services;

use App\Enums\AlicuotaEnum;
use App\Services\AFIPService;
use App\Models\Cabfac;
use Illuminate\Support\Carbon;
use App\Models\Detfac;
use App\Enums\DescarteEnum;
use App\Enums\EstadosFacturaEnum;
use Exception;
use App\Services\EntidadService;
use App\Services\LoteService;
use App\Models\Remates;
use App\Enums\TipoComprobanteEnum;
use App\Enums\SerieEnum;
use App\Enums\PuntoVentaEnum;

class FacturaService
{

    public function index(array $data = [])
    {
        $per_page = $data['per_page'] ?? 1;
        $sort_by = $data['sort_by'] ?? 'codnum';
        $sort_order = $data['sort_order'] ?? 'desc';
        $query = Cabfac::query();

        // Aplicar orden
        $query->with('tcomp', 'serie', 'cliente', 'ncomp')->orderBy($sort_by, $sort_order);

        return $query->paginate($per_page);
    }

    public function show(int $ncomp)
    {
        $data = Remates::where('ncomp', $ncomp)->with('tcomp', 'serie', 'cliente', 'ncomp')->first();
        if (!$data)
            throw new \Exception("No existe la Subasta");
        return $data;
    }

    public function crearAlicuotaIva($alicuotaId, $baseImp)
    {
        $alicuota = AlicuotaEnum::from($alicuotaId)->valorAlicuota();
        $importe = $baseImp * $alicuota;

        return [
            'Id'      => $alicuotaId,
            'BaseImp' => $baseImp,
            'Importe' => $importe,
        ];
    }

    private function crearFactura($data, $tcomp, $serie)
    {
            $cerosdb = DescarteEnum::CEROSDB->value;
            $cabfac = new CabfacService;
            $ncomp = $cabfac->ncomp($tcomp) + 1;
            $totiva105 = $cerosdb;
            $totiva21 = $cerosdb;
            $totNeto105 = $cerosdb;
            $totNeto21 = $cerosdb;
            foreach ($data['comprobante']['renglones'] as $renglon) {

                $cargarDetFac = [
                    'tcomp' => $tcomp,
                    'serie' => $serie,
                    'ncomp' => $ncomp,
                    'nreng' => count($data['comprobante']['renglones']),
                    'codrem' => $data['comprobante']['codrem'],
                    'codlote' => $renglon['codlote'],
                    'descrip' => $renglon['desc'],
                    'neto' => $renglon['impunit'],
                    'bruto' => $renglon['impunit'] + $renglon['impiva'],
                    'iva' => $renglon['tipoiva'],
                    'comcob' => $cerosdb,
                    'compag' => $cerosdb,
                    'usuario' => 1,
                    'porciva' => $renglon['tipoiva'],
                    'concafac' => $renglon['codigoconc'],
                ];

                $this->cargarDetFac($cargarDetFac);

                $comision = $renglon['comision'] * $renglon['impunit'] / DescarteEnum::CIEN->value;
                $gsadm = $renglon['gsadmin'] * DescarteEnum::GASTOADM->value / DescarteEnum::CIEN->value;
                $gsadmin = $renglon['impunit'] * $gsadm / DescarteEnum::CIEN->value;
            }

            // INSERTAR CABFAC
            $entidad = new EntidadService;
            $codcli = $entidad->codnum($data['comprobante']['cuit']);

            $impUsoPlat = $cerosdb;

            $cargarCabFac = [
                'tcomp' => $tcomp,
                'serie' => $serie,
                'ncomp' => $ncomp,
                'fecval' => $data['comprobante']['fecha'],
                'fecdoc' => $data['comprobante']['fecha'],
                'fecreg' => $data['comprobante']['fecha'],
                'cliente' => $codcli,
                'fecvenc' => $data['comprobante']['fecha'],
                'codrem' => $data['comprobante']['codrem'],
                'estado' => EstadosFacturaEnum::PENDIENTE->value,
                'emitido' => $cerosdb,
                'moneda' => $cerosdb,
                'totneto' => $data['comprobante']['impneto'],
                'totbruto' => $data['comprobante']['imptotal'],
                'totiva105' => $totiva105,
                'totiva21' => $totiva21 + $impUsoPlat,
                'totimp' => $gsadmin,
                'totcomis' => $comision,
                'totneto105' => $totNeto105,
                'totneto21' => $totNeto21,
                'nrengs' => count($data['comprobante']['renglones']),
                'usuario' => 1,
                'nrodoc' => EstadosFacturaEnum::NRDOCB->value,
                'en_liquid' => $data['comprobante']['en_liquid'],
                'CAE' => null,
                'CAEFchVto' => null,
                'Resultado' => EstadosFacturaEnum::APROBADO->value,
                'usuarioultmod' => 1
            ];

            $this->cargarCabFac($cargarCabFac);

            return "Numero de Comprobante: ". $ncomp;

    }

    // DETFAC
    private function cargarDetFac(array $cargarDetFac)
    {
            Detfac::create($cargarDetFac);
    }

    // CABFAC
    private function cargarCabFac(array $cargarCabFac)
    {
            Cabfac::create($cargarCabFac);
    }


    public function crearFacturaA(array $data = [])
    {
        $tcomp = TipoComprobanteEnum::FACTURA_LOT_SIST_B->value;
        $serie = SerieEnum::FACTURA_SERIE_SISTEMA_B->value;
        return $this->crearFactura($data, $tcomp, $serie);
    }
}

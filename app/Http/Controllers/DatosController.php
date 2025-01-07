<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paises;
use App\Models\Provincias;
use App\Models\Localidades;
use App\Models\Tipoenti;
use App\Models\Tipoivas;
use App\Models\Tipoindustria;

class DatosController extends Controller
{
    public function getPaises()
    {
        return Paises::all();
    }

    public function getProvincias()
    {
        return Provincias::all();
    }

    public function getLocalidades()
    {
        return Localidades::all();
    }

    public function getTiposEntidad()
    {
        return Tipoenti::all();
    }

    public function getTiposIVA()
    {
        return Tipoivas::all();
    }

    public function getTiposIndustria()
    {
        return Tipoindustria::all();
    }
}

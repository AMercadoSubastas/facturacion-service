<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Paises;
use App\Models\Localidades;
use App\Models\Provincias;
use App\Models\Entidades;
use App\Models\Tipoindustria;

class Remates extends Model
{
    protected $table = 'remates';

    protected $fillable = [
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'codcli',
        'direccion',
        'codpais',
        'codprov',
        'codloc',
        'fecest',
        'fecreal',
        'imptot',
        'impbase',
        'estado',
        'cantlotes',
        'horaest',
        'horareal',
        'usuario',
        'fecalta',
        'observacion',
        'tipoind',
        'sello',
        'plazoSAP',
        'usuarioultmod',
        'fecultmod',
        'servicios',
        'gastos',
        'tasa'
    ];

    public function codpais()
    {
        return $this->hasOne(Paises::class, 'codnum', 'codpais');
    }

    public function codprov()
    {
        return $this->hasOne(Provincias::class, 'codnum', 'codprov');
    }

    public function codloc()
    {
        return $this->hasOne(Localidades::class, 'codnum', 'codloc');
    }

    public function codcli()
    {
        return $this->hasOne(Entidades::class, 'codnum', 'codcli');
    }

    public function tipoind()
    {
        return $this->hasOne(Tipoindustria::class, 'codnum', 'tipoind');
    }
}

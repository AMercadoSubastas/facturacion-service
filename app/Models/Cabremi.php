<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cabfac;
use App\Models\Tipcomp;
use App\Models\Series;
use App\Models\Entidades;

class Cabremi extends Model
{
    protected $table = 'cabremi';

    protected $fillable = [
        'cabremi',
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'cantrengs',
        'comprador',
        'fecharemi',
        'observaciones',
        'calle',
        'numero',
        'pisodto',
        'codpais',
        'codprov',
        'codloc',
        'codpost',
        'patente',
        'patremolque',
        'cuit',
        'fechahora',
        'usuario',
        'tcomprel',
        'serierel',
        'ncomprel',
        'usuarioultmod',
        'fechaultmod',
    ];

    public function tipoComp()
    {
        return $this->hasOne(TipComp::class, 'codnum', 'tcomp');
    }

    public function serie()
    {
        return $this->hasOne(Series::class, 'codnum', 'serie');
    }

    public function tcomprel()
    {
        return $this->hasOne(TipComp::class, 'codnum', 'tcomprel');
    }

    public function serierel()
    {
        return $this->hasOne(Series::class, 'codnum', 'serierel');
    }

    public function ncomprel()
    {
        return $this->hasOne(Cabfac::class, 'ncomp', 'ncomprel');
    }
}

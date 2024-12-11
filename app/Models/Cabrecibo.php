<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tipcomp;
use App\Models\Series;
use App\Models\Entidades;

class Cabrecibo extends Model
{
    protected $table = 'cabrecibo';

    protected $fillable = [
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'cantcbtes',
        'fecha',
        'usuario',
        'fechahora',
        'cliente',
        'imptot',
        'emitido',
        'usuarioultmod',
        'fecultmod',
    ];

    public function tipoComp()
    {
        return $this->hasOne(TipComp::class, 'codnum', 'tcomp');
    }

    public function serie()
    {
        return $this->hasOne(Series::class, 'codnum', 'serie');
    }

    public function entidad()
    {
        return $this->hasOne(Entidades::class, 'codnum', 'cliente');
    }

}

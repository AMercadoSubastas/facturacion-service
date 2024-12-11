<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tipcomp;
use App\Models\Series;
use App\Models\Entidades;
use App\Models\Detfac;
use \Awobaz\Compoships\Compoships;

class Cabfac extends Model
{

    protected $table = 'cabfac';
    
    use Compoships;

    protected $fillable = [
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'fecval',
        'fecdoc',
        'fecreg',
        'cliente',
        'cpago',
        'fecvenc',
        'direc',
        'dnro',
        'pisodto',
        'codpost',
        'codpais',
        'codprov',
        'codloc',
        'telef',
        'codrem',
        'estado',
        'emitido',
        'moneda',
        'totneto',
        'totbruto',
        'totiva105',
        'totiva21',
        'totimp',
        'totcomis',
        'totneto105',
        'totneto21',
        'tipoiva',
        'porciva',
        'nrengs',
        'fechahora',
        'usuario',
        'tieneresol',
        'leyendafc',
        'concepto',
        'nrodoc',
        'tcompsal',
        'seriesal',
        'ncompsal',
        'en_liquid',
        'CAE',
        'CAEFchVto',
        'Resultado',
        'usuarioultmod',
        'fecultmod',
    ];

    public function tcomp()
    {
        return $this->hasOne(TipComp::class, 'codnum', 'tcomp');
    }

    public function serie()
    {
        return $this->hasOne(Series::class, 'codnum', 'serie');
    }

    public function cliente()
    {
        return $this->hasOne(Entidades::class, 'codnum', 'cliente');
    }

    public function ncomp()
    {
        return $this->hasMany(Detfac::class, ['tcomp', 'serie', 'ncomp', 'codrem'], ['tcomp', 'serie', 'ncomp', 'codrem']);
    }


}

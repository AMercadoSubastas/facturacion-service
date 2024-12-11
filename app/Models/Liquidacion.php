<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidacion';

    protected $fillable = [
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'cliente',
        'rubro',
        'calle',
        'dnro',
        'pisodto',
        'codpost',
        'codpais',
        'codprov',
        'codloc',
        'codrem',
        'fecharem',
        'cuit',
        'tipoiva',
        'totremate',
        'totneto1',
        'totiva21',
        'subtot1',
        'totneto2',
        'totiva105',
        'subtot2',
        'totacuenta',
        'totgastos',
        'totvarios',
        'saldoafav',
        'fechahora',
        'usuario',
        'fechaliq',
        'estado',
        'nrodoc',
        'cotiz',
        'usuarioultmod',
        'fecultmod',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cartvalores extends Model
{
    protected $table = 'cartvalores';

    protected $fillable = [
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'codban',
        'codsuc',
        'codcta',
        'tipcta',
        'codchq',
        'codpais',
        'importe',
        'fechaemis',
        'fechapago',
        'entrego',
        'recibio',
        'fechaingr',
        'fechaentrega',
        'tcomprel',
        'serierel',
        'ncomprel',
        'estado',
        'moneda',
        'fechahora',
        'usuario',
        'tcompsal',
        'seriesal',
        'ncompsal',
        'codrem',
        'cotiz',
        'usurel',
        'fecharel',
        'ususal',
        'fechasal',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concafact extends Model
{
    protected $table = 'concafact';

    protected $fillable = [
        'codnum',
        'nroconc',
        'descrip',
        'porcentaje',
        'importe',
        'usuario',
        'fechahora',
        'activo',
        'tipoiva',
        'impuesto',
        'tieneresol',
        'ctacbleBAS',
    ];
}

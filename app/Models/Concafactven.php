<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concafactven extends Model
{
    protected $table = 'concafactven';

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impuestos extends Model
{
    protected $table = 'impuestos';

    protected $fillable = [
        'codnum',
        'porcen',
        'descripcion',
        'rangos',
        'montomin',
        'activo'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificacion';

    protected $fillable = [
        'codnum',
        'descor',
        'descripcion',
        'activo',
    ];
}

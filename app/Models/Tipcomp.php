<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipcomp extends Model
{
    protected $table = 'tipcomp';

    protected $fillable = [
        'codnum',
        'descripcion',
        'activo',
        'esfactura',
        'esprovedor',
        'codafip',
        'usuarioalta',
        'fechaalta',
        'usuariomod',
        'fechaultmod'
    ];
}

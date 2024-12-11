<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirRemates extends Model
{
    protected $table = 'dir_remates';

    protected $fillable = [
        'codigo',
        'codrem',
        'secuencia',
        'direccion',
        'codpais',
        'codprov',
        'codloc',
        'usuarioalta',
        'fechaalta',
        'usuariomod',
        'fechaultmod'
    ];
}

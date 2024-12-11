<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detremi extends Model
{
    protected $table = 'detremi';

    protected $fillable = [
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'nreng',
        'codlote',
        'desclote',
        'descorlote',
        'estado',
        'fechahora'
    ];
}

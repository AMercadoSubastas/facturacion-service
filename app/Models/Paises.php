<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    protected $table = 'paises';

    protected $fillable = [
        'codnum',
        'descripcion',
        'activo'
    ];

}

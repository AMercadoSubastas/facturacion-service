<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucbancos extends Model
{
    protected $table = 'sucbancos';

    protected $fillable = [
        'codnum',
        'codpais',
        'codbanco',
        'codsuc',
        'nombre',
        'activo'
    ];
}

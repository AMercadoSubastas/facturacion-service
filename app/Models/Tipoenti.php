<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipoenti extends Model
{
    protected $table = 'tipoenti';

    protected $fillable = [
        'codnum',
        'descripcion',
        'activo',
    ];
}

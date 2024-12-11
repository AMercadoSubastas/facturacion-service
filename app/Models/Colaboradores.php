<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaboradores extends Model
{
    protected $table = 'Usuarios';

    protected $fillable = [
        'codnum',
        'usuario',
        'nombre',
        'clave',
        'nivel',
        'email',
        'perfil',
        'activo',
    ];
}

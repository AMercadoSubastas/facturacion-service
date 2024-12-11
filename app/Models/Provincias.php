<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Paises;

class Provincias extends Model
{
    protected $table = 'provincias';

    protected $fillable = [
        'codnum',
        'codpais',
        'descripcion',
        'activo'
    ];

    public function pais()
    {
        return $this->hasOne(Paises::class, 'codnum', 'codpais');
    }

}

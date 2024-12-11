<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Paises;
use App\Models\Provincias;

class Localidades extends Model
{
    protected $table = 'localidades';

    protected $fillable = [
        'codnum',
        'codpais',
        'codprov',
        'descripcion',
        'activo',
    ];

    public function pais()
    {
        return $this->hasOne(Paises::class, 'codnum', 'codpais');
    }

    public function provincia()
    {
        return $this->hasOne(Provincias::class, 'codnum', 'codprov');
    }

}

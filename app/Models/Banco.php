<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Paises;


class Banco extends Model
{
    protected $table = 'bancos';

    protected $fillable = [
        'codnum',
        'codbanco',
        'nombre',
        'codpais',
        'activo',
    ];

    public function pais()
    {
        return $this->hasOne(Paises::class, 'codnum', 'codpais');
    }
}

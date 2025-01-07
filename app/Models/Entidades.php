<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Paises;
use App\Models\Localidades;
use App\Models\Provincias;
use App\Models\Tipoenti;
use App\Models\Tipoivas;
use App\Models\Tipoindustria;

class Entidades extends Model
{
    protected $table = 'entidades';
    protected $primaryKey = 'codnum';


    protected $fillable = [
        'codnum',
        'razsoc',
        'calle',
        'numero',
        'pisodto',
        'codpais',
        'codprov',
        'codloc',
        'codpost',
        'tellinea',
        'telcelu',
        'tipoent',
        'tipoiva',
        'cuit',
        'calif',
        'fecalta',
        'usuario',
        'contacto',
        'mailcont',
        'cargo',
        'fechahora',
        'activo',
        'pagweb',
        'tipoind',
        'usuarioultmod',
        'fecultmod'
    ];

    public function codpais()
    {
        return $this->hasOne(Paises::class, 'codnum', 'codpais');
    }

    public function codprov()
    {
        return $this->hasOne(Provincias::class, 'codnum', 'codprov');
    }

    public function codloc()
    {
        return $this->hasOne(Localidades::class, 'codnum', 'codloc');
    }

    public function tipoenti()
    {
        return $this->hasOne(Tipoenti::class, 'codnum', 'tipoent');
    }

    public function tipoiva()
    {
        return $this->hasOne(Tipoivas::class, 'codnum', 'tipoiva');
    }

    public function tipoind()
    {
        return $this->hasOne(Tipoindustria::class, 'codnum', 'tipoind');
    }
}

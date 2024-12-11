<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Tipcomp;

class Series extends Model
{
    protected $table = 'series';

    protected $fillable = [
        'codnum',
        'tipcomp',
        'descripcion',
        'nrodesde',
        'nrohasta',
        'nroact',
        'mascara',
        'activo',
        'automatica',
        'fechatope'
    ];

    public function tipoComp()
    {
        return $this->hasOne(TipComp::class, 'codnum', 'tipcomp');
    }
}
